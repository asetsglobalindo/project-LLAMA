<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Services\ExternalApiService;
use League\CommonMark\CommonMarkConverter;

class AIController extends Controller
{
    private string $groqApiUrl = 'https://api.groq.com/openai/v1/chat/completions';
    private string $model = 'llama-3.1-8b-instant';
    private ExternalApiService $externalApiService;
    private CommonMarkConverter $markdownConverter;

    public function __construct(ExternalApiService $externalApiService)
    {
        $this->externalApiService = $externalApiService;
        $this->markdownConverter = new CommonMarkConverter();
    }

    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'context' => 'nullable|array',
        ]);

        $userMessage = (string) $request->input('message');
        $context = (array) $request->input('context', []);
        $detectedLanguage = $this->detectLanguage($userMessage);

        // Early handling for greetings: let AI generate brief reply, no recommendations
        if ($this->isGreeting($userMessage)) {
            try {
                $aiReply = $this->generateAIResponse($userMessage, [], null);
                $content = trim($aiReply['content'] ?? '');
                if ($content === '') {
                    $content = $this->greetingReply($detectedLanguage);
                }
                return response()->json([
                    'success' => true,
                    'data' => [
                        'message' => $content,
                        'recommendations' => [],
                        'context' => ['type' => 'greeting'],
                        'timestamp' => now()->toISOString(),
                    ],
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'message' => $this->greetingReply($detectedLanguage),
                        'recommendations' => [],
                        'context' => ['type' => 'greeting_fallback'],
                        'timestamp' => now()->toISOString(),
                    ],
                ]);
            }
        }

        try {
            // Build conversation history with context
            $conversationHistory = $this->buildConversationHistory($context, $userMessage);
            
            // Siapkan konteks data untuk AI (lokasi terfilter + budget + jenis usaha)
            $latestBudgetInfo = $this->extractBudgetInfo($userMessage);
            $budgetValue = $latestBudgetInfo['value'] ?? null;
            $budgetDirection = $latestBudgetInfo['direction'] ?? null;
            $businessType = $this->detectBusinessType($userMessage);

            $allLocations = $this->loadAllLocationData();
            $filteredContext = $this->buildDataContext($userMessage, $allLocations, $budgetValue, $budgetDirection, $businessType);

            $response = $this->generateAIResponse($userMessage, $conversationHistory, $filteredContext);

            // Hanya tambahkan rekomendasi jika user sudah mengkonfirmasi ingin melihat rekomendasi
            $wantsRecommendations = $this->userWantsRecommendations($userMessage, $conversationHistory);
                $hasIntent = $this->hasActionableIntent($userMessage) || $this->hasIntentFromContext($conversationHistory);
            // Hanya kirim rekomendasi saat user memang memintanya DAN pertanyaan bukan minta estimasi/analisis umum
            // Catatan: kemunculan kata "budget" saja bukan indikasi estimasi; fokus pada pertanyaan nominal seperti "berapa/kisaran/range"
            $isEstimationQuestion = (bool) preg_match('/(berapa|kisaran|range(\s+harga)?)/iu', $userMessage);
            if ($wantsRecommendations && $hasIntent && !$isEstimationQuestion) {
                $localRecs = $this->generateLocalRecommendationsWithContext($userMessage, $detectedLanguage, $conversationHistory);
                if (!empty($localRecs)) {
                    $response['recommendations'] = $localRecs;
                    // Override AI free-form message with grounded summary from our data
                    $response['content'] = $this->buildNaturalSummary($userMessage, $localRecs, $detectedLanguage);
                }
            }
            
            // Check if user wants database analysis (without links)
            if ($this->userWantsDatabaseAnalysis($userMessage, $conversationHistory)) {
                $analysis = $this->generateDatabaseAnalysis($userMessage, $detectedLanguage, $conversationHistory);
                if (!empty($analysis)) {
                    $response['database_analysis'] = $analysis;
                }
            }

            // Biarkan AI yang menjawab. Hanya jika kosong total, beri fallback singkat.
            if (empty(trim($response['content'] ?? ''))) {
                $response['content'] = $detectedLanguage === 'en' 
                    ? 'Please describe the city, size (m² or L x W), and monthly budget so I can help with recommendations.'
                    : 'Baik, silakan jelaskan kota, ukuran (m² atau P x L), dan budget per bulan agar saya bisa bantu rekomendasi.';
            }

            // Convert markdown to HTML for structured response
            $structuredMessage = $this->formatStructuredResponse($response['content'], $response['recommendations'] ?? []);

            return response()->json([
                'success' => true,
                'data' => [
                    'message' => $response['content'],
                    'structured_message' => $structuredMessage,
                    'recommendations' => $response['recommendations'] ?? [],
                    'context' => $response['context'] ?? [],
                    'timestamp' => now()->toISOString(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('AI Chat Error: ' . $e->getMessage());

            $fallbackResponse = $this->generateFallbackResponse($userMessage);

            return response()->json([
                'success' => true,
                'data' => [
                    'message' => $fallbackResponse['content'],
                    'recommendations' => $this->generateLocalRecommendations($userMessage, $detectedLanguage),
                    'context' => ['type' => 'fallback_response', 'reason' => 'groq_api_unavailable'],
                    'timestamp' => now()->toISOString(),
                ],
            ]);
        }
    }

    private function generateAIResponse(string $message, array $conversationHistory = [], ?array $dataContext = null): array
    {
        $detectedLanguage = $this->detectLanguage($message);
        $systemPrompt = $this->getSystemPrompt($detectedLanguage);

        // Analyze conversation context for better understanding
        $conversationContext = $this->analyzeConversationContext($conversationHistory, $message);

        // Build messages array with conversation history
        $messages = [['role' => 'system', 'content' => $systemPrompt]];
        
        // Add conversation context analysis for better memory
        if (!empty($conversationContext['topics']) || $conversationContext['budget_mentioned'] || $conversationContext['timeframe_mentioned']) {
            $contextSummary = $this->buildContextSummary($conversationContext, $detectedLanguage);
            $messages[] = [
                'role' => 'system',
                'content' => "CONVERSATION_MEMORY: " . $contextSummary
            ];
        }
        
        // Add conversation history (limit to last 10 messages to avoid token limit)
        $recentHistory = array_slice($conversationHistory, -10);
        foreach ($recentHistory as $msg) {
            if (isset($msg['role']) && isset($msg['content'])) {
                // Clean content for better context understanding
                $cleanContent = strip_tags($msg['content']);
                $cleanContent = preg_replace('/\s+/', ' ', $cleanContent);
                $cleanContent = trim($cleanContent);
                
                if (!empty($cleanContent)) {
                    $messages[] = [
                        'role' => $msg['role'],
                        'content' => $cleanContent
                    ];
                }
            }
        }
        
        // Attach structured data context for grounding (if any)
        if ($dataContext !== null) {
            $messages[] = [
                'role' => 'system',
                'content' => "CONTEXT_DATA (JSON): " . json_encode($dataContext, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            ];
        }
        
        // Add current user message
        $messages[] = ['role' => 'user', 'content' => $message];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type' => 'application/json',
        ])->withOptions([
            'verify' => false,
            'timeout' => 30,
        ])->post($this->groqApiUrl, [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => 0.2,
            'max_tokens' => 800,
            'stream' => false,
        ]);

        if ($response->successful()) {
            $responseData = $response->json();
            $aiContent = $responseData['choices'][0]['message']['content'] ?? '';

            return [
                'content' => $aiContent,
                'recommendations' => [],
                'context' => ['type' => 'groq_response', 'model' => $this->model],
            ];
        }

        throw new \RuntimeException('Groq API request failed: ' . $response->body());
    }

    private function getSystemPrompt(string $language = 'id'): string
    {
        if ($language === 'en') {
            return <<<'PROMPT'
You are AVIS AI (Asets Virtual Intelligence Systems) — a credible, communicative, and strategic business consultant.

✨ Your Identity:
"I'm AVIS AI, your credible business bestie — ready to be your loyal assistant and trusted executor for all things strategy, analysis, and business planning."

🧠 Core Role & Capabilities:

1. READ AND REMEMBER CONVERSATION CONTEXT
- You MUST understand all previous messages (topics, goals, data, or decisions mentioned)
- Never answer without reading context first
- If user says something that connects to previous discussion, continue from there
- If user suddenly says something weird, short, or unclear, interpret meaning from previous context first
- If still ambiguous, ask for clarification with a light and friendly tone

2. CONTEXTUAL AND RELEVANT
- All answers must align with the latest conversation direction
- Handle topics like investment, business strategy, market research, business ideas, product development
- Use RAG (retrieval or knowledge base) when you need current data, real examples, or location references

3. HANDLE WEIRD OR INFORMAL INPUT
- If user uses emojis, typos, or jokes, respond with bestie style but stay professional
- Convert unclear speech into meaningful sentences, then answer the main context
- Example: User: "yang bisa ngebut tapi santai hehe" → You: "Hehe noted 😄 you want fast results but still stable right? Let me help you find balanced options."

4. MULTI-DOMAIN (not just gold)
- Handle investment, business planning, business ideas, market research, marketing strategy, operations, pricing, growth, technology, etc.
- If user changes topics, connect smoothly

5. COMMUNICATION STYLE:
- Warm, smart, and engaging like a professional bestie
- Use natural modern Indonesian but stay polite
- Avoid being too rigid or formal
- Use emojis moderately for human touch (✨, 💬, 🚀, 💡, etc.)

6. IDEAL ANSWER STRUCTURE:
💬 Context summary: what's being discussed or user's goal
📊 Brief analysis: insights from context, data, or opportunities
🎯 Recommendations/execution steps: concrete actions that can be taken
(optional) 1-line clarification question if context is incomplete

7. CONTINUOUS CONTEXT HANDLING (memory):
- Save important details like: budget, location, business type, target time, user's risk style
- Reuse in next conversation without user needing to repeat
- If information changes, update old memory

8. PROFESSIONAL BUT EMPATHETIC ATTITUDE:
- If user seems hesitant, give positive support
- If user asks for execution ("please create strategy, analysis, etc"), help directly with neat and actionable format

Default opening: "Hello! 👋 I'm AVIS AI, your credible business bestie. Ready to help you analyze, strategize, and execute your business ideas together 💼✨."

Default for weird/unclear input: "Hehe, do you mean this connects to the previous topic? Let me make sure so my answer is accurate."
PROMPT;
        }
        
        // Default Indonesian prompt (grounded to provided context)
        return <<<'PROMPT'
Kamu adalah AVIS AI (Asets Virtual Intelligence Systems) — konsultan bisnis yang credible, komunikatif, dan berpikiran strategis.

✨ Identitas kamu:
"Saya AVIS AI, credible bestie bisnis kamu — siap jadi asisten setia dan eksekutor andalan buat bantuin semua hal soal strategi, analisis, dan rencana bisnis kamu."

🧠 Peran & Kemampuan Utama:

1. MEMBACA DAN MENGINGAT KONTEKS PERCAKAPAN
- Kamu HARUS memahami semua pesan sebelumnya (topik, tujuan, data, atau keputusan yang sudah disebut)
- Jangan jawab tanpa membaca konteks dulu
- Jika user bilang sesuatu yang nyambung ke sebelumnya, lanjutkan dari situ
- Jika user tiba-tiba ngomong aneh, singkat, atau nggak jelas, tafsirkan maknanya dari konteks sebelumnya dulu
- Jika tetap ambigu, tanyakan klarifikasi dengan nada ringan dan ramah

2. KONTEKSTUAL DAN RELEVAN
- Semua jawaban harus menyesuaikan dengan arah pembicaraan terakhir
- Tangani topik seperti investasi, strategi bisnis, riset pasar, ide usaha, pengembangan produk
- Gunakan RAG (retrieval atau knowledge base) jika perlu data terkini, contoh nyata, atau referensi lokasi

3. MENANGANI INPUT ANEH ATAU TIDAK FORMAL
- Jika user pakai emoji, typo, atau bercanda, tanggapi dengan gaya bestie tapi tetap profesional
- Ubah ucapan tidak jelas menjadi kalimat bermakna, lalu jawab konteks utamanya
- Contoh: User: "yang bisa ngebut tapi santai hehe" → Kamu: "Hehe noted 😄 maksudnya kamu mau hasil cepat tapi tetap stabil ya? Aku bantu cariin opsi seimbangnya."

4. MULTI-DOMAIN (tidak hanya emas)
- Tangani investasi, perencanaan bisnis, ide usaha, riset pasar, strategi marketing, operasional, pricing, growth, teknologi, dll
- Kalau user berganti topik, sambungkan dengan mulus

5. GAYA KOMUNIKASI:
- Hangat, smart, dan engaging seperti bestie profesional
- Gunakan bahasa alami Indonesia modern tapi tetap sopan
- Hindari terlalu kaku atau terlalu formal
- Gunakan emoji secukupnya untuk human touch (✨, 💬, 🚀, 💡, dll)

6. STRUKTUR JAWABAN IDEAL:
💬 Ringkasan konteks: apa yang sedang dibahas atau tujuan user
📊 Analisis singkat: insight dari konteks, data, atau peluangnya
🎯 Rekomendasi / langkah eksekusi: tindakan konkret yang bisa dilakukan
(opsional) pertanyaan klarifikasi 1 baris kalau konteks belum lengkap

7. PENANGANAN KONTEKS BERKELANJUTAN (memori):
- Simpan detail penting seperti: budget, lokasi, jenis bisnis, target waktu, dan gaya risiko user
- Gunakan ulang di percakapan berikut tanpa user perlu ulangi
- Jika informasi berubah, update memori lama

8. SIKAP PROFESIONAL TAPI EMPATIK:
- Jika user tampak ragu, beri dukungan positif
- Jika user minta eksekusi ("tolong buatin strategi, analisis, dll"), langsung bantu dengan format rapi dan actionable

Kalimat default pembuka: "Halo! 👋 Saya AVIS AI, credible bestie bisnis kamu. Siap bantuin kamu analisis, strategi, dan eksekusi ide bisnismu bareng-bareng 💼✨."

Kalimat default jika input aneh / tidak nyambung: "Hehe, maksud kamu yang tadi masih nyambung ke topik sebelumnya ya? Biar aku pastiin dulu biar jawabanku tepat."
PROMPT;
    }

    private function generateFallbackResponse(string $message): array
    {
        $detectedLanguage = $this->detectLanguage($message);
        
        // Extract information from user message
        $budgetInfo = $this->extractBudgetInfo($message);
        $mentionedCities = $this->getMentionedCities($message);
        $businessType = $this->detectBusinessType($message);
        
        // Load location data for recommendations
        $allLocationData = $this->loadAllLocationData();
        
        // Generate recommendations based on extracted info
        $recommendations = $this->generateLocalRecommendations($allLocationData, $budgetInfo, $mentionedCities, $businessType);
        
        if ($detectedLanguage === 'en') {
            $response = "I understand you're looking for commercial space. ";
            if (!empty($mentionedCities)) {
                $response .= "I can help you find locations in " . implode(', ', $mentionedCities) . ". ";
            }
            if ($budgetInfo['value'] !== null) {
                $response .= "With your budget of IDR " . number_format($budgetInfo['value'], 0, ',', '.') . " per month, ";
            }
            $response .= "I have some great options for you. ";
            
            if (!empty($recommendations)) {
                $response .= "Here are the top locations I'd recommend for your business:";
            } else {
                $response .= "Let me know more about your specific needs and I'll find the perfect location for you.";
            }
        } else {
            $response = "Saya mengerti Anda sedang mencari ruang komersial. ";
            if (!empty($mentionedCities)) {
                $response .= "Saya bisa membantu Anda menemukan lokasi di " . implode(', ', $mentionedCities) . ". ";
            }
            if ($budgetInfo['value'] !== null) {
                $response .= "Dengan budget Anda sebesar IDR " . number_format($budgetInfo['value'], 0, ',', '.') . " per bulan, ";
            }
            $response .= "saya punya beberapa pilihan bagus untuk Anda. ";
            
            if (!empty($recommendations)) {
                $response .= "Berikut lokasi terbaik yang saya rekomendasikan untuk bisnis Anda:";
            } else {
                $response .= "Ceritakan lebih detail tentang kebutuhan spesifik Anda, dan saya akan temukan lokasi yang sempurna untuk Anda.";
            }
        }
        
        return [
            'content' => $response,
            'recommendations' => $recommendations,
            'context' => ['type' => 'fallback_response', 'reason' => 'groq_api_unavailable'],
        ];
    }

    private function buildDefaultReply(string $userMessage, array $recs, string $language = 'id'): string
    {
        $lower = mb_strtolower($userMessage, 'UTF-8');
        $city = null;
        foreach (['jakarta','bogor','depok','tangerang','bandung','bengkulu','jember','banjarmasin','cilacap','ciamis','serang','bsd city','kuningan','cilincing','ancol','wanareja','bojongmengger'] as $c) {
            if (str_contains($lower, $c)) { $city = ucfirst($c); break; }
        }

        $budgetInfo = $this->extractBudgetInfo($userMessage);
        $budgetValue = $budgetInfo['value'] ?? null;
        $budgetLabel = $budgetValue !== null ? 'IDR ' . number_format((float)$budgetValue, 0, ',', '.') . ' per bulan' : null;

        if ($language === 'en') {
            $parts = [];
            $parts[] = 'Here are recommendations that match your needs.';
            if ($city) $parts[] = 'City: ' . $city . '.';
            if ($budgetLabel) $parts[] = 'Budget: ' . str_replace('.', ',', $budgetLabel);
            if (!empty($recs)) {
                $parts[] = 'I include some relevant location links.';
            } else {
                $parts[] = 'No matches from local data; try mentioning different size or budget range.';
            }
            $parts[] = 'If needed, I can send you the links and other alternatives.';
            return implode(' ', $parts);
        }

        $parts = [];
        $parts[] = 'Berikut rekomendasi yang sesuai dengan kebutuhan Anda.';
        if ($city) $parts[] = 'Kota: ' . $city . '.';
        if ($budgetLabel) $parts[] = 'Budget: ' . $budgetLabel . '.';
        if (!empty($recs)) {
            $parts[] = 'Saya sertakan beberapa tautan lokasi yang relevan.';
        } else {
            $parts[] = 'Belum ada yang cocok dari data lokal; coba sebutkan ukuran atau rentang budget lain.';
        }
        $parts[] = 'Jika butuh, saya bisa kirimkan linknya dan alternatif lainnya.';
        return implode(' ', $parts);
    }

    private function buildNoMatchReply(string $userMessage, string $language = 'id'): string
    {
        $lower = mb_strtolower($userMessage, 'UTF-8');
        $city = null;
        foreach (['jakarta','bogor','depok','tangerang','bandung','bengkulu','jember','banjarmasin','cilacap','ciamis','serang','bsd city','kuningan','cilincing','ancol','wanareja','bojongmengger'] as $c) {
            if (str_contains($lower, $c)) { $city = ucfirst($c); break; }
        }
        $budgetInfo = $this->extractBudgetInfo($userMessage);
        $budgetValue = $budgetInfo['value'] ?? null;
        $budgetLabelId = $budgetValue !== null ? 'IDR ' . number_format((float)$budgetValue, 0, ',', '.') : null;

        if ($language === 'en') {
            $msg = 'Sorry, no suitable locations found in our data for those criteria.';
            if ($city || $budgetLabelId) {
                $msg = 'Sorry, no suitable locations found';
                if ($city) $msg .= ' in ' . $city;
                if ($budgetLabelId) $msg .= ' with budget ' . str_replace('.', ',', $budgetLabelId) . ' per month';
                $msg .= '.';
            }
            return $msg;
        }

        $msg = 'Maaf, belum ada lokasi yang cocok dari data kami untuk kriteria tersebut.';
        if ($city || $budgetLabelId) {
            $msg = 'Maaf, belum ada lokasi yang cocok';
            if ($city) $msg .= ' di ' . $city;
            if ($budgetLabelId) $msg .= ' dengan budget ' . $budgetLabelId . ' per bulan';
            $msg .= '.';
        }
        return $msg;
    }

    private function buildNaturalSummary(string $userMessage, array $recs, string $language = 'id'): string
    {
        $lower = mb_strtolower($userMessage, 'UTF-8');
        $explicit = $this->hasExplicitSelection($userMessage);
        $city = null;
        foreach (['jakarta','bogor','depok','tangerang','bandung','bengkulu','jember','banjarmasin','cilacap','ciamis','serang','bsd city','kuningan','cilincing','ancol','wanareja','bojongmengger'] as $c) {
            if (str_contains($lower, $c)) { $city = ucfirst($c); break; }
        }
        // Jika user memilih lokasi spesifik, jangan analisis atau sebut budget
        $budgetInfo = $explicit ? ['value' => null, 'direction' => null] : $this->extractBudgetInfo($userMessage);
        $budgetValue = $budgetInfo['value'] ?? null;
        $budgetDirection = $budgetInfo['direction'] ?? null; // 'min' | 'max' | null

        // Build budget label for messages
        $budgetLabelId = $budgetValue !== null ? 'IDR ' . number_format((float)$budgetValue, 0, ',', '.') : null;
        $budgetLabelEn = $budgetValue !== null ? 'IDR ' . number_format((float)$budgetValue, 0, ',', '.') : null;

        if ($language === 'en') {
            if ($explicit) {
                return 'Here is the location you selected.';
            }
            // Determine match proximity
            $hasNearBudget = false; $allFarBelow = true;
            foreach ($recs as $r) {
                if ($budgetValue !== null && isset($r['price_value']) && $r['price_value'] !== null) {
                    $priceVal = (float)$r['price_value'];
                    if ($priceVal >= ($budgetValue * 0.8) && $priceVal <= ($budgetValue * 1.05)) { $hasNearBudget = true; }
                    if (!($priceVal <= $budgetValue * 0.5)) { $allFarBelow = false; }
                }
            }

            $open = 'I found several options that can be considered';
            if ($city) $open .= ' in ' . $city;
            if ($budgetLabelEn) {
                $open .= ($budgetDirection === 'min')
                    ? ' with minimum ' . str_replace('.', ',', $budgetLabelEn) . ' per month'
                    : ' with range ' . str_replace('.', ',', $budgetLabelEn) . ' per month';
            }
            $open .= '.';

            // Summarize top 1-2 areas with budget reasoning
            $highlights = [];
            foreach (array_slice($recs, 0, 2) as $r) {
                $desc = $r['description'] ?? '';
                $reason = '';
                if ($budgetValue !== null && isset($r['price_value']) && $r['price_value'] !== null) {
                    $priceVal = (float)$r['price_value'];
                    $tolerance = $budgetValue * 1.05; // 5% above budget
                    if ($priceVal <= $budgetValue * 0.5) {
                        $reason = ' chosen because the starting price is far below your budget';
                    } elseif ($priceVal <= $budgetValue) {
                        $reason = $budgetLabelEn ? (' chosen because the starting price fits your ' . $budgetLabelEn . ' per month budget') : ' chosen because the starting price fits your budget';
                    } elseif ($priceVal <= $tolerance) {
                        $reason = $budgetLabelEn ? (' slightly above budget but still close to ' . $budgetLabelEn . ' per month') : ' slightly above but still close to your budget';
                    }
                }
                $highlights[] = $desc . $reason;
            }
            $mid = count($highlights) ? 'Examples: ' . implode('; ', $highlights) . '.' : '';

            return trim($open . ' ' . $mid);
        }

        // Jika explicit selection, fokuskan pada lokasi tanpa pembahasan budget
        if ($explicit) {
            return 'Berikut lokasi yang Anda pilih.';
        }

        // Tentukan kedekatan dengan budget
        $hasNearBudget = false; $allFarBelow = true;
        foreach ($recs as $r) {
            if ($budgetValue !== null && isset($r['price_value']) && $r['price_value'] !== null) {
                $priceVal = (float)$r['price_value'];
                if ($priceVal >= ($budgetValue * 0.8) && $priceVal <= ($budgetValue * 1.05)) { $hasNearBudget = true; }
                if (!($priceVal <= $budgetValue * 0.5)) { $allFarBelow = false; }
            }
        }

        $open = 'Saya menemukan beberapa opsi yang bisa dipertimbangkan';
        if ($city) $open .= ' di ' . $city;
        if ($budgetLabelId) {
            $open .= ($budgetDirection === 'min')
                ? ' dengan minimal ' . $budgetLabelId . ' per bulan'
                : ' dengan kisaran ' . $budgetLabelId . ' per bulan';
        }
        $open .= '.';

        // Ringkas 1-2 area teratas dengan alasan kecocokan budget
        $highlights = [];
        foreach (array_slice($recs, 0, 2) as $r) {
            $desc = $r['description'] ?? '';
            $reason = '';
            if ($budgetValue !== null && isset($r['price_value']) && $r['price_value'] !== null) {
                $priceVal = (float)$r['price_value'];
                $tolerance = $budgetValue * 1.05; // toleransi 5%
                if ($priceVal <= $budgetValue * 0.5) {
                    $reason = ' dipilih karena harga mulai jauh di bawah budget Anda';
                } elseif ($priceVal <= $budgetValue) {
                    $reason = $budgetLabelId ? (' dipilih karena harga mulai sesuai dengan budget ' . $budgetLabelId . ' per bulan') : ' dipilih karena harga mulai sesuai dengan budget Anda';
                } elseif ($priceVal <= $tolerance) {
                    $reason = $budgetLabelId ? (' sedikit di atas budget namun masih dekat dengan ' . $budgetLabelId . ' per bulan') : ' sedikit di atas namun masih dekat dengan budget Anda';
                }
            }
            $highlights[] = $desc . $reason;
        }
        $mid = count($highlights) ? 'Contoh: ' . implode('; ', $highlights) . '.' : '';

        return trim($open . ' ' . $mid);
    }

    public function status(): JsonResponse
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false,
                'timeout' => 5,
            ])->post($this->groqApiUrl, [
                'model' => $this->model,
                'messages' => [ ['role' => 'user', 'content' => 'test'] ],
                'max_tokens' => 1,
            ]);
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'groq_api_available' => true,
                    'model_available' => true,
                    'model' => $this->model,
                    'message' => 'Groq API berfungsi dengan baik',
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Groq API Status Check Error: ' . $e->getMessage());
        }
        return response()->json([
            'success' => false,
            'groq_api_available' => false,
            'model_available' => false,
            'message' => 'Groq API tidak tersedia. Menggunakan fallback response.',
        ]);
    }

    /**
     * Test external API integration
     */
    public function testExternalApi(): JsonResponse
    {
        try {
            $listings = $this->externalApiService->fetchListings();
            $spaces = $this->externalApiService->fetchSpaceAvailable();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'listings_count' => count($listings),
                    'spaces_count' => count($spaces),
                    'listings_sample' => array_slice($listings, 0, 3),
                    'spaces_sample' => array_slice($spaces, 0, 3),
                ],
                'message' => 'External API integration test successful'
            ]);
        } catch (\Exception $e) {
            Log::error('External API Test Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'External API test failed: ' . $e->getMessage(),
                'data' => []
            ]);
        }
    }

    /**
     * Clear external API cache
     */
    public function clearApiCache(): JsonResponse
    {
        try {
            $this->externalApiService->clearCache();
            
            return response()->json([
                'success' => true,
                'message' => 'External API cache cleared successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Clear Cache Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Rekomendasi lokal analitis berbasis file JSON komersial dengan analisis mendalam.
     */
    private function generateLocalRecommendations(string $userMessage, string $language = 'id', ?float $forcedBudget = null, ?string $forcedDirection = null): array
    {
        try {
            // Baca data dari kedua file JSON
            $locations = $this->loadAllLocationData();

            // Jika user menyebutkan lokasi spesifik (nama lengkap atau ID), fokus hanya pada lokasi itu
            $explicit = $this->findExplicitLocationMatch($userMessage, $locations);
            if ($explicit !== null) {
                $locations = [$explicit];
                // Saat user memilih spesifik, abaikan filter kota/budget agar tidak menyaring yang sudah dipilih
                $forcedBudget = null;
                $forcedDirection = null;
            }

            // Deteksi banyak kota sekaligus dari pesan user
            $mentionedCities = $this->getMentionedCities($userMessage);

            // Ambil info budget (nilai + arah: minimal/maksimal)
            $budgetInfo = $this->extractBudgetInfo($userMessage);
            $budget = $forcedBudget !== null ? $forcedBudget : $budgetInfo['value'];
            $direction = $forcedDirection !== null ? $forcedDirection : $budgetInfo['direction']; // 'min' | 'max' | null

            // Deteksi jenis usaha dari pesan
            $businessType = $this->detectBusinessType($userMessage);

            // Grupkan kandidat per listing dan gabungkan ukuran
            $grouped = [];
            foreach ($locations as $loc) {
                // Extract city from address
                $address = $loc['address'] ?? '';
                $locationCity = $this->extractCityFromAddress($address);
                // Disambiguasi data: jika address mengandung "Medan Satria" anggap Bekasi
                if ($locationCity === 'Medan' && (str_contains(mb_strtolower($address, 'UTF-8'), 'medan satria') || preg_match('/\bkota\s*bks\b/u', mb_strtolower($address, 'UTF-8')))) {
                    $locationCity = 'Bekasi';
                }

                // Filter dengan himpunan kota yang disebut user (jika ada)
                if (!empty($mentionedCities)) {
                    $isAllowed = false;
                    foreach ($mentionedCities as $mc) {
                        if (mb_strtolower($locationCity, 'UTF-8') === mb_strtolower($mc, 'UTF-8')) { $isAllowed = true; break; }
                    }
                    if (!$isAllowed) { continue; }
                }
                
                $key = $loc['id'] . '|' . $loc['name'];
                foreach (($loc['spaces'] ?? []) as $space) {
                    $price = $space['price'] ?? null;
                    $type = $space['price_type'] ?? 'm2';
                    $spaceSize = $space['space_size'] ?? null;
                    
                    if ($budget !== null && $price !== null) {
                        $tolerance = 0.05; // 5% toleransi di atas budget
                        if ($direction === 'min') {
                            if ($price < $budget) { continue; }
                        } else { // 'max' atau null dianggap batas atas
                            $maxAllowed = $budget * (1 + $tolerance);
                            if ($price > $maxAllowed) { continue; }
                        }
                    }
                    
                    if (!isset($grouped[$key])) {
                        $grouped[$key] = [
                            'id' => $loc['id'],
                            'name' => $loc['name'],
                            'address' => $address,
                            'city' => $locationCity,
                            'cover' => $loc['cover'] ?? null,
                            'sizes' => [],
                            'min_price' => $price !== null ? (float)$price : null,
                            'price_type' => $type,
                            'spaces' => []
                        ];
                    }
                    
                    if ($spaceSize !== null) {
                        $grouped[$key]['sizes'][] = (float)$spaceSize;
                    }
                    
                    $grouped[$key]['spaces'][] = $space;
                    
                    if ($price !== null) {
                        if ($grouped[$key]['min_price'] === null || (float)$price < $grouped[$key]['min_price']) {
                            $grouped[$key]['min_price'] = (float)$price;
                            $grouped[$key]['price_type'] = $type;
                        }
                    }
                }
            }
            

            // Analisis dan scoring untuk setiap kandidat
            $candidates = [];
            foreach ($grouped as $g) {
                if (empty($g['sizes']) && $g['min_price'] === null) continue;
                
                // Analisis potensi lokasi
                $analysis = $this->analyzeLocationPotential($g, $businessType, $budget, $language);
                
                $candidates[] = [
                    'id' => $g['id'],
                    'name' => $g['name'],
                    'city' => $g['city'],
                    'address' => $g['address'],
                    'cover' => $g['cover'] ?? null,
                    'price' => $g['min_price'],
                    'price_type' => $g['price_type'],
                    'source' => 'private_sector',
                    'analysis' => $analysis,
                    'score' => $analysis['score'],
                    'sizes' => $g['sizes'],
                    'spaces' => $g['spaces']
                ];
            }

            // Urutkan berdasarkan score analisis (bukan hanya harga)
            usort($candidates, function ($a, $b) {
                return $b['score'] <=> $a['score'];
            });

            $top = array_slice($candidates, 0, 15);
            $recs = [];
            foreach ($top as $rec) {
                $priceLabel = isset($rec['price'])
                    ? 'IDR ' . number_format((float)$rec['price'], 0, ',', '.') . ($rec['price_type'] === 'm2' ? ' / m² / Month' : ' / Month')
                    : ($language === 'en' ? 'Contact for price' : 'Hubungi untuk harga');
                
                // Generate image URL if cover exists
                $imageUrl = null;
                if (!empty($rec['cover'])) {
                    // Check if it's a full URL or a local path
                    if (str_starts_with($rec['cover'], 'http')) {
                        $imageUrl = $rec['cover'];
                    } else {
                        $imageUrl = asset('storage/' . $rec['cover']);
                    }
                } else {
                    // Default image for locations without cover
                    $imageUrl = 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&h=600';
                }
                
                $recs[] = [
                    'description' => $rec['address'] ?? '',
                    'price' => $priceLabel,
                    'link' => '/detail-spbu/' . $rec['id'],
                    'image' => $imageUrl,
                    'id' => $rec['id'],
                    'source' => $rec['source'] ?? 'private_sector',
                    'city' => $rec['city'] ?? '',
                    'price_value' => $rec['price'],
                    'price_type' => $rec['price_type'] ?? 'Lot',
                    'analysis' => $rec['analysis'],
                    'location_description' => $this->generateLocationDescription($rec, $language)
                ];
            }
            
            return $recs;
        } catch (\Throwable $e) {
            Log::warning('Local recommendation error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Format structured response with HTML and markdown
     */
    private function formatStructuredResponse(string $message, array $recommendations = []): string
    {
        $detectedLanguage = $this->detectLanguage($message);
        
        // Extract budget info for structured display
        $budgetInfo = $this->extractBudgetInfo($message);
        $budgetValue = $budgetInfo['value'] ?? null;
        $budgetDirection = $budgetInfo['direction'] ?? null;
        
        // Extract mentioned cities
        $mentionedCities = $this->getMentionedCities($message);
        
        // Extract business type
        $businessType = $this->detectBusinessType($message);
        
        // Build structured HTML response
        $html = '<div class="ai-structured-response">';
        
        // Main message section
        $html .= '<div class="ai-main-message">';
        $html .= '<h3 class="ai-section-title">💬 Pesan Utama</h3>';
        $html .= '<div class="ai-message-content">' . $this->markdownConverter->convert($message) . '</div>';
        $html .= '</div>';
        
        // Analysis section if we have recommendations
        if (!empty($recommendations)) {
            $html .= '<div class="ai-analysis-section">';
            $html .= '<h3 class="ai-section-title">📊 Analisis Kebutuhan</h3>';
            $html .= '<div class="ai-analysis-content">';
            
            // Budget analysis - REMOVED as requested
            
            // Location analysis
            if (!empty($mentionedCities)) {
                $html .= '<div class="ai-analysis-item">';
                $html .= '<h4 class="ai-subtitle">📍 Lokasi Target</h4>';
                $html .= '<p class="ai-description">' . implode(', ', $mentionedCities) . '</p>';
                $html .= '</div>';
            }
            
            // Business type analysis
            if ($businessType !== 'umum') {
                $html .= '<div class="ai-analysis-item">';
                $html .= '<h4 class="ai-subtitle">🏢 Jenis Usaha</h4>';
                $html .= '<p class="ai-description">' . ucfirst($businessType) . '</p>';
                $html .= '</div>';
            }
            
            $html .= '</div>';
            $html .= '</div>';
            
            // Recommendations summary
            $html .= '<div class="ai-recommendations-summary">';
            $html .= '<h3 class="ai-section-title">🎯 Ringkasan Rekomendasi</h3>';
            $html .= '<div class="ai-summary-content">';
            $html .= '<p class="ai-description">Ditemukan <strong>' . count($recommendations) . ' lokasi</strong> yang sesuai dengan kriteria Anda:</p>';
            
            // Price range analysis
            $prices = array_filter(array_column($recommendations, 'price_value'));
            if (!empty($prices)) {
                $minPrice = min($prices);
                $maxPrice = max($prices);
                $html .= '<div class="ai-price-range">';
                $html .= '<h4 class="ai-subtitle">💵 Kisaran Harga</h4>';
                $html .= '<p class="ai-description">IDR ' . number_format($minPrice, 0, ',', '.') . ' - IDR ' . number_format($maxPrice, 0, ',', '.') . ' per bulan</p>';
                $html .= '</div>';
            }
            
            // Top recommendations preview
            $topRecs = array_slice($recommendations, 0, 3);
            $html .= '<div class="ai-top-recommendations">';
            $html .= '<h4 class="ai-subtitle">⭐ Rekomendasi Teratas</h4>';
            $html .= '<ul class="ai-recommendations-list">';
            foreach ($topRecs as $rec) {
                $html .= '<li class="ai-recommendation-item">';
                $html .= '<span class="ai-location">' . htmlspecialchars($rec['description']) . '</span>';
                $html .= '<br><span class="ai-price">' . htmlspecialchars($rec['price']) . '</span>';
                $html .= '</li>';
            }
            $html .= '</ul>';
            $html .= '</div>';
            
            $html .= '</div>';
            $html .= '</div>';
        }
        
        // Tips section - REMOVED as requested
        
        $html .= '</div>';
        
        return $html;
    }

    /**
     * Extract city from address string
     */
    private function extractCityFromAddress(string $address): string
    {
        $lower = mb_strtolower($address, 'UTF-8');
        
        // Disambiguation rules BEFORE general matching
        // 1) "Medan Satria" is a district in Bekasi → prefer Bekasi if present
        if ((str_contains($lower, 'medan satria')) && (str_contains($lower, 'bekasi') || preg_match('/\bkota\s*bks\b/u', $lower))) {
            return 'Bekasi';
        }
        // 2) Abbreviation handling: "Kota Bks" → Bekasi
        if (preg_match('/\bkota\s*bks\b/u', $lower)) {
            return 'Bekasi';
        }
        
        // Known cities to look for (prioritize exact matches)
        // Note: Put cities with possible collisions BEFORE similar tokens (e.g., Bekasi before Medan)
        $cities = [
            'bengkulu' => 'Bengkulu',
            'jember' => 'Jember', 
            'banjarmasin' => 'Banjarmasin',
            'cilacap' => 'Cilacap',
            'ciamis' => 'Ciamis',
            'serang' => 'Serang',
            'depok' => 'Depok',
            'tangerang' => 'Tangerang',
            'bogor' => 'Bogor',
            'bandung' => 'Bandung',
            'jakarta' => 'Jakarta',
            'bsd city' => 'Tangerang',
            'kuningan' => 'Jakarta',
            'cilincing' => 'Jakarta',
            'ancol' => 'Jakarta',
            'wanareja' => 'Cilacap',
            'bojongmengger' => 'Ciamis',
            'downtown' => 'Jakarta',
            'north jakarta' => 'Jakarta',
            'south jakarta' => 'Jakarta',
            'jkt utara' => 'Jakarta',
            'daerah khusus ibukota jakarta' => 'Jakarta',
            // Additional cities from external data
            'bekasi' => 'Bekasi',
            'medan' => 'Medan',
            'palembang' => 'Palembang',
            'denpasar' => 'Denpasar',
            'yogyakarta' => 'Yogyakarta',
            'solo' => 'Solo',
            'malang' => 'Malang',
            'surabaya' => 'Surabaya',
            'manado' => 'Manado',
            'pekanbaru' => 'Pekanbaru',
            'padang' => 'Padang',
            'semarang' => 'Semarang',
            'makassar' => 'Makassar',
            'balikpapan' => 'Balikpapan'
        ];
        
        // Check for exact city matches first
        foreach ($cities as $cityKey => $cityName) {
            if (str_contains($lower, $cityKey)) {
                return $cityName;
            }
        }
        
        // Check for "Jakarta, Indonesia" pattern
        if (str_contains($lower, 'jakarta') && str_contains($lower, 'indonesia')) {
            return 'Jakarta';
        }
        
        // Check for any Jakarta mention (avoid false positives by ensuring not part of other city tokens)
        if (str_contains($lower, 'jakarta')) {
            return 'Jakarta';
        }
        
        return 'Unknown';
    }

    /**
     * Ekstrak semua kota yang disebut user (mendukung multi-kota) dengan disambiguasi.
     */
    private function getMentionedCities(string $message): array
    {
        $lower = mb_strtolower($message, 'UTF-8');
        $knownCities = ['jakarta','bogor','depok','tangerang','bandung','bengkulu','jember','banjarmasin','cilacap','ciamis','serang','bsd city','kuningan','cilincing','ancol','wanareja','bojongmengger','medan','palembang','denpasar','yogyakarta','solo','malang','surabaya','manado','bekasi','pekanbaru','padang','semarang','makassar','balikpapan'];
        $found = [];
        foreach ($knownCities as $c) {
            if (str_contains($lower, $c)) {
                $city = ($c === 'bsd city') ? 'Tangerang' : ucfirst($c);
                $found[$city] = true;
            }
        }
        // Disambiguasi khusus: "medan satria" → Bekasi
        if (str_contains($lower, 'medan satria') || preg_match('/\bkota\s*bks\b/u', $lower)) {
            unset($found['Medan']);
            $found['Bekasi'] = true;
        }
        return array_keys($found);
    }

    /**
     * Cari kecocokan eksplisit berdasarkan nama lokasi atau ID yang disebut user.
     * Mengembalikan array lokasi (format internal loadAllLocationData) jika ketemu, selain itu null.
     */
    private function findExplicitLocationMatch(string $userMessage, array $locations): ?array
    {
        $m = mb_strtolower($userMessage, 'UTF-8');
        // Pola umum yang menunjukkan pemilihan spesifik
        $selectionHints = [
            'ini deh', 'ini saja', 'ini aja', 'yang itu', 'itu saja', 'itu aja',
            'pilih ini', 'ambillah ini', 'ambil ini', 'pakai yang ini', 'pakai ini',
            'tolong tampilkan lokasinya', 'tampilkan lokasinya', 'tunjukkan lokasinya',
            'yang spbu', 'yang ruko', 'yang villa', 'yang kantor'
        ];

        $hintFound = false;
        foreach ($selectionHints as $h) {
            if (str_contains($m, $h)) { $hintFound = true; break; }
        }

        // Ekstrak potensi ID SPBU seperti 11.201.104 atau 31.129.02 dlsb
        $idCandidates = [];
        if (preg_match_all('/\b(\d{1,2}\.\d{2,3}\.\d{2,3})\b/u', $userMessage, $mm)) {
            $idCandidates = array_map('strval', $mm[1]);
        }

        // Coba cocokkan berdasarkan ID dulu
        if (!empty($idCandidates)) {
            foreach ($locations as $loc) {
                $nameLower = mb_strtolower($loc['name'] ?? '', 'UTF-8');
                foreach ($idCandidates as $cand) {
                    if (str_contains($nameLower, mb_strtolower($cand, 'UTF-8'))) {
                        return $loc;
                    }
                }
            }
        }

        // Jika menyebut nama lengkap lokasi (mis. "SPBU COCO 11.201.104 Medan Gatot Subroto"), cocokkan fuzzy berprioritas tinggi
        // Ambil frasa yang mulai dengan spbu/ruko/villa/kantor hingga 8-10 kata ke depan
        if (preg_match('/\b(spbu|ruko|villa|kantor)[^\n]{5,120}/iu', $userMessage, $mname)) {
            $needle = trim($mname[0]);
            $needleLower = mb_strtolower($needle, 'UTF-8');
            $best = null; $bestScore = 0;
            foreach ($locations as $loc) {
                $hay = mb_strtolower(($loc['name'] ?? '') . ' ' . ($loc['address'] ?? ''), 'UTF-8');
                $score = 0;
                // Skor sederhana: jumlah kata needle yang muncul di hay
                foreach (preg_split('/\s+/u', $needleLower) as $w) {
                    if (mb_strlen($w, 'UTF-8') < 2) continue;
                    if (str_contains($hay, $w)) { $score++; }
                }
                if ($score > $bestScore) { $bestScore = $score; $best = $loc; }
            }
            if ($best !== null && $bestScore >= 3) { // ambang sederhana
                return $best;
            }
        }

        // Jika ada hint pemilihan spesifik plus menyebut satu nama lokasi yang punya kecocokan kuat terhadap salah satu item, ambil itu
        if ($hintFound) {
            $best = null; $bestScore = 0;
            foreach ($locations as $loc) {
                $hay = mb_strtolower(($loc['name'] ?? '') . ' ' . ($loc['address'] ?? ''), 'UTF-8');
                $score = 0;
                foreach (preg_split('/\s+/u', $m) as $w) {
                    if (mb_strlen($w, 'UTF-8') < 3) continue;
                    if (str_contains($hay, $w)) { $score++; }
                }
                if ($score > $bestScore) { $bestScore = $score; $best = $loc; }
            }
            if ($best !== null && $bestScore >= 5) {
                return $best;
            }
        }

        return null;
    }

    /**
     * True jika pesan menunjukkan pemilihan lokasi spesifik (nama/ID + frasa memilih)
     */
    private function hasExplicitSelection(string $userMessage): bool
    {
        $m = mb_strtolower($userMessage, 'UTF-8');
        // Jika ada pola ID SPBU, anggap explicit
        if (preg_match('/\b(\d{1,2}\.\d{2,3}\.\d{2,3})\b/u', $userMessage)) {
            return true;
        }
        $selectionHints = [
            'ini deh', 'ini saja', 'ini aja', 'yang itu', 'itu saja', 'itu aja',
            'pilih ini', 'ambillah ini', 'ambil ini', 'pakai yang ini', 'pakai ini',
            'tolong tampilkan lokasinya', 'tampilkan lokasinya', 'tunjukkan lokasinya'
        ];
        foreach ($selectionHints as $h) { if (str_contains($m, $h)) return true; }
        // Nama entitas kuat di awal
        if (preg_match('/\b(spbu|ruko|villa|kantor)\b/iu', $userMessage)) return true;
        return false;
    }

    /**
     * Ekstrak informasi budget dari pesan user: nilai, arah (min/max), dan teks mentah
     */
    private function extractBudgetInfo(string $message): array
    {
        // Bersihkan baris-baris harga list (mis. hasil rekomendasi) agar tidak mengganggu parsing budget
        $message = $this->stripPriceLines($message);
        // Abaikan pola ID seperti 11.201.104 agar tidak dianggap angka budget
        $message = preg_replace('/\b\d{1,2}\.\d{2,3}\.\d{2,3}\b/u', '', $message);
        $lower = mb_strtolower($message, 'UTF-8');
        $direction = null; // 'min' atau 'max'
        if (str_contains($lower, 'minimal') || str_contains($lower, 'di atas') || str_contains($lower, '>=') || str_contains($lower, 'lebih dari') || str_contains($lower, 'minimum') || str_contains($lower, 'min ')) {
            $direction = 'min';
        } elseif (str_contains($lower, 'maksimal') || str_contains($lower, 'maximum') || str_contains($lower, 'max ') || str_contains($lower, 'di bawah') || str_contains($lower, '<=') || str_contains($lower, 'kurang dari')) {
            $direction = 'max';
        }

        $raw = null;
        $value = null;

        // Deteksi periode (per bulan vs per tahun)
        $isPerYear = (bool) preg_match('/(per\s*tahun|pertahun|setahun)/iu', $lower);
        // Anggap default per bulan jika tidak disebutkan

        // Dukungan untuk satuan miliar, juta, ribu dengan angka lokal (titik/koma)
        if (preg_match('/(\d+[\.,]?\d*)\s*(miliar)/iu', $message, $m)) {
            $num = $this->parseLocalizedNumber($m[1]);
            $value = $num * 1000000000;
            $raw = trim($m[0]);
        } elseif (preg_match('/(\d+[\.,]?\d*)\s*(juta|jt)/iu', $message, $m)) {
            $num = $this->parseLocalizedNumber($m[1]);
            $value = $num * 1000000;
            $raw = trim($m[0]);
        } elseif (preg_match('/(\d+[\.,]?\d*)\s*(ribu|rb)/iu', $message, $m)) {
            $num = $this->parseLocalizedNumber($m[1]);
            $value = $num * 1000;
            $raw = trim($m[0]);
        } elseif (preg_match('/\b(\d{1,3}(?:[\.,]\d{3})+)\b/u', $message, $m)) {
            // Angka dengan pemisah ribuan, contoh: 300.000.000 atau 300,000,000
            $value = (float) preg_replace('/\D+/', '', $m[1]);
            $raw = trim($m[1]);
        } elseif (preg_match('/\b(\d{5,9})\b/u', $message, $m)) {
            // Angka polos 5-9 digit dianggap nilai rupiah langsung
            $value = (float) $m[1];
            $raw = trim($m[1]);
        } elseif (preg_match('/\b(\d{1,4})\b/u', $message, $m) && preg_match('/\b(ribu|rb|juta|jt|miliar)\b/iu', $message)) {
            // Jika ada unit namun angka pendek, jangan auto kalikan tanpa unit; ditangani kasus di atas
            // Biarkan sebagai null di sini agar tidak salah tafsir
        }

        // Konversi per tahun -> per bulan
        if ($value !== null && $isPerYear) {
            $value = $value / 12.0;
        }

        return ['value' => $value, 'direction' => $direction, 'raw' => $raw];
    }

    /**
     * Parse localized number where '.' and ',' may represent thousand/decimal separators.
     * Strategy: if both present, last occurrence char is decimal sep; remove others as thousand.
     * If only one present, assume it's thousand separator for Indonesian context.
     */
    private function parseLocalizedNumber(string $numStr): float
    {
        $s = trim($numStr);
        $lastDot = strrpos($s, '.');
        $lastComma = strrpos($s, ',');

        if ($lastDot !== false && $lastComma !== false) {
            // Both present: decide decimal as the later one
            if ($lastDot > $lastComma) {
                // '.' is decimal, ',' thousands
                $s = str_replace(',', '', $s);
                return (float) str_replace('.', '.', $s); // no-op for clarity
            } else {
                // ',' is decimal, '.' thousands
                $s = str_replace('.', '', $s);
                $s = str_replace(',', '.', $s);
                return (float) $s;
            }
        }

        // Only one of them exists: treat as thousand separator (common in ID formatting)
        $s = str_replace(['.', ','], '', $s);
        return (float) $s;
    }

    /**
     * Hapus baris-baris yang berisi pola harga list (IDR xxx / Month, dsb) agar tidak mempengaruhi parsing budget
     */
    private function stripPriceLines(string $text): string
    {
        $lines = preg_split('/\r?\n/', $text);
        $kept = [];
        foreach ($lines as $line) {
            $l = mb_strtolower($line, 'UTF-8');
            if (str_contains($l, 'idr') || str_contains($l, '/ month') || str_contains($l, '/ m²') || str_contains($l, '/ m2')) {
                // lewati baris harga list
                continue;
            }
            $kept[] = $line;
        }
        return implode("\n", $kept);
    }

    private function isGreeting(string $message): bool
    {
        $m = mb_strtolower(trim($message), 'UTF-8');
        return (bool) preg_match('/\b(halo|hai|hi|hello|pagi|siang|sore|malam)\b/u', $m);
    }

    private function greetingReply(string $language = 'id'): string
    {
        if ($language === 'en') {
            return 'Hello, would you like me to help find a suitable business location?';
        }
        
        return 'Halo, mau saya bantu menemukan lokasi bisnis yang cocok untuk Anda?';
    }

    private function hasActionableIntent(string $message): bool
    {
        $m = mb_strtolower($message, 'UTF-8');
        $mentionsCity = (bool) preg_match('/\b(jakarta|bogor|depok|tangerang|bandung|bengkulu|jember|banjarmasin|cilacap|ciamis|serang|bsd city|kuningan|cilincing|ancol|wanareja|bojongmengger|medan|palembang|denpasar|yogyakarta|solo|malang|surabaya|manado|bekasi|pekanbaru|padang|semarang|makassar|balikpapan)\b/u', $m);
        $mentionsBudget = (bool) preg_match('/\b(\d+[\.,]?\d*\s*(juta|jt|ribu|rb)|\d{5,9})\b/u', $m);
        $mentionsSize = (bool) preg_match('/\b(\d+\s*x\s*\d+|m2|m²)\b/u', $m);
        return $mentionsCity || $mentionsBudget || $mentionsSize;
    }

    /**
     * Deteksi bahasa input pengguna (Indonesia vs Inggris)
     */
    private function detectLanguage(string $message): string
    {
        // Bersihkan boilerplate/hint UI berbahasa Inggris yang terkadang ikut terkirim
        // Contoh: "I can help you find space recommendations ... Please mention the city ..."
        $sanitized = preg_replace(
            '/I\s+can\s+help\s+you\s+find\s+space\s+recommendations[\s\S]*?(?:budget\.|budget)/imu',
            '',
            $message
        );

        // Trim dan normalisasi ke huruf kecil
        $lower = mb_strtolower(trim($sanitized), 'UTF-8');

        // Jika user secara eksplisit minta bahasa Inggris
        if (preg_match('/\b(english|bahasa\s+inggris|reply\s+in\s+english)\b/u', $lower)) {
            return 'en';
        }
        
        // Kata kunci bahasa Indonesia (dengan bobot lebih tinggi)
        $indonesianKeywords = [
            'halo', 'hai', 'selamat', 'pagi', 'siang', 'sore', 'malam',
            'saya', 'aku', 'saya mau', 'saya cari', 'saya butuh',
            'ruang', 'lokasi', 'tempat', 'area',
            'budget', 'anggaran', 'harga', 'biaya', 'per bulan',
            'ukuran', 'besar', 'kecil', 'meter', 'm²', 'm2',
            'juta', 'jt', 'ribu', 'rb', 'rupiah',
            'buka', 'usaha', 'toko', 'restoran', 'cafe',
            'cocok', 'sesuai', 'rekomendasi', 'saran',
            'bisa', 'boleh', 'mohon', 'tolong'
        ];
        
        // Kata kunci bahasa Inggris (dengan bobot lebih tinggi)
        $englishKeywords = [
            'hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening',
            'i', 'i want', 'i need', 'i am looking', 'i am searching',
            'location', 'place', 'area', 'commercial',
            'price', 'cost', 'per month', 'monthly',
            'size', 'big', 'small', 'square meter', 'sqm', 'sq m',
            'million', 'thousand', 'k', 'dollar', 'usd',
            'open', 'business', 'store', 'restaurant', 'cafe',
            'suitable', 'recommend', 'suggestion', 'advice',
            'can', 'could', 'please', 'help', 'assist'
        ];
        
        // Kata kunci netral (kota, space, budget) - tidak dihitung
        $neutralKeywords = ['jakarta', 'bogor', 'depok', 'tangerang', 'bandung', 'space', 'budget'];
        
        $indonesianCount = 0;
        $englishCount = 0;
        
        // Hitung kata kunci Indonesia
        foreach ($indonesianKeywords as $keyword) {
            if (str_contains($lower, $keyword)) {
                $indonesianCount++;
            }
        }
        
        // Hitung kata kunci Inggris
        foreach ($englishKeywords as $keyword) {
            if (str_contains($lower, $keyword)) {
                $englishCount++;
            }
        }
        
        // Jika ada kata kunci yang jelas, prioritaskan Indonesia bila campuran
        if ($indonesianCount > 0 || $englishCount > 0) {
            if ($indonesianCount > 0 && $englishCount === 0) return 'id';
            if ($englishCount > 0 && $indonesianCount === 0) return 'en';
            // Campuran: default ke Indonesia
            return 'id';
        }
        
        // Deteksi berdasarkan pola kata-kata umum
        $hasIndonesianPattern = preg_match('/\b(saya|aku|mau|cari|butuh|bisa|boleh|tolong|mohon)\b/u', $lower);
        $hasEnglishPattern = preg_match('/\b(i|am|want|need|looking|searching|can|could|please|help)\b/u', $lower);
        
        if ($hasIndonesianPattern && !$hasEnglishPattern) {
            return 'id';
        }
        
        if ($hasEnglishPattern && !$hasIndonesianPattern) {
            return 'en';
        }
        
        // Deteksi berdasarkan kata sambung dan preposisi
        $hasIndonesianConnectors = preg_match('/\b(di|untuk|dengan|dari|ke|pada|dalam)\b/u', $lower);
        $hasEnglishConnectors = preg_match('/\b(in|for|with|from|to|at|on|by)\b/u', $lower);
        
        if ($hasIndonesianConnectors && !$hasEnglishConnectors) {
            return 'id';
        }
        
        if ($hasEnglishConnectors && !$hasIndonesianConnectors) {
            return 'en';
        }
        
        // Default ke Indonesia jika tidak bisa dideteksi
        return 'id';
    }

    /**
     * Build conversation history from context array
     */
    private function buildConversationHistory(array $context, string $currentMessage): array
    {
        $history = [];
        
        // Process context messages
        foreach ($context as $msg) {
            if (is_array($msg) && isset($msg['role']) && isset($msg['content'])) {
                // Skip assistant_typing messages
                if ($msg['role'] !== 'assistant_typing') {
                    $history[] = [
                        'role' => $msg['role'],
                        'content' => $msg['content']
                    ];
                }
            }
        }
        
        return $history;
    }

    /**
     * Check if there's actionable intent from conversation history
     */
    private function hasIntentFromContext(array $conversationHistory): bool
    {
        $fullConversation = '';
        foreach ($conversationHistory as $msg) {
            if (isset($msg['content'])) {
                $fullConversation .= ' ' . $msg['content'];
            }
        }
        
        return $this->hasActionableIntent($fullConversation);
    }

    /**
     * Analyze conversation context to understand user's current topic and needs
     */
    private function analyzeConversationContext(array $conversationHistory, string $currentMessage): array
    {
        $context = [
            'topics' => [],
            'budget_mentioned' => false,
            'timeframe_mentioned' => false,
            'location_mentioned' => false,
            'business_type' => null,
            'investment_type' => null,
            'user_goals' => [],
            'risk_profile' => null,
            'previous_decisions' => []
        ];

        // Combine all conversation history
        $fullConversation = '';
        foreach ($conversationHistory as $msg) {
            if (isset($msg['content']) && $msg['role'] === 'user') {
                $fullConversation .= ' ' . $msg['content'];
            }
        }
        $fullConversation .= ' ' . $currentMessage;

        // Detect topics mentioned
        $topics = [
            'investasi' => ['investasi', 'investment', 'invest', 'modal', 'capital'],
            'emas' => ['emas', 'gold', 'logam mulia', 'precious metal'],
            'properti' => ['properti', 'property', 'rumah', 'tanah', 'real estate'],
            'saham' => ['saham', 'stock', 'bursa', 'trading'],
            'crypto' => ['crypto', 'bitcoin', 'ethereum', 'cryptocurrency', 'mata uang digital'],
            'bisnis' => ['bisnis', 'business', 'usaha', 'wirausaha', 'entrepreneur'],
            'ruang komersial' => ['ruang komersial', 'commercial space', 'kantor', 'toko', 'ruko', 'rukan'],
            'teknologi' => ['teknologi', 'technology', 'tech', 'digital', 'software', 'aplikasi'],
            'coding' => ['coding', 'programming', 'develop', 'kode', 'aplikasi', 'website'],
            'marketing' => ['marketing', 'pemasaran', 'promosi', 'iklan', 'branding'],
            'operasional' => ['operasional', 'operations', 'produksi', 'manajemen'],
            'strategi' => ['strategi', 'strategy', 'perencanaan', 'planning']
        ];

        foreach ($topics as $topic => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($fullConversation, $keyword) !== false) {
                    $context['topics'][] = $topic;
                    break;
                }
            }
        }

        // Detect budget mentions
        $context['budget_mentioned'] = $this->extractBudgetInfo($fullConversation)['value'] !== null;
        
        // Detect timeframe mentions
        $timeframeKeywords = ['bulan', 'month', 'tahun', 'year', 'minggu', 'week', 'hari', 'day', 'jangka pendek', 'jangka panjang', 'short term', 'long term', 'cepat', 'fast', 'lambat', 'slow'];
        foreach ($timeframeKeywords as $keyword) {
            if (stripos($fullConversation, $keyword) !== false) {
                $context['timeframe_mentioned'] = true;
                break;
            }
        }

        // Detect location mentions
        $context['location_mentioned'] = !empty($this->getMentionedCities($fullConversation));

        // Detect business type
        $context['business_type'] = $this->detectBusinessType($fullConversation);

        // Detect investment type
        $investmentTypes = ['emas', 'properti', 'saham', 'crypto', 'reksadana', 'obligasi'];
        foreach ($investmentTypes as $type) {
            if (stripos($fullConversation, $type) !== false) {
                $context['investment_type'] = $type;
                break;
            }
        }

        // Detect risk profile
        $riskKeywords = [
            'konservatif' => ['konservatif', 'conservative', 'aman', 'safe', 'stabil'],
            'moderat' => ['moderat', 'moderate', 'seimbang', 'balanced'],
            'agresif' => ['agresif', 'aggressive', 'tinggi', 'high risk', 'spekulatif']
        ];
        foreach ($riskKeywords as $profile => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($fullConversation, $keyword) !== false) {
                    $context['risk_profile'] = $profile;
                    break 2;
                }
            }
        }

        // Extract user goals from conversation
        $goalKeywords = ['ingin', 'want', 'mau', 'tujuan', 'goal', 'target', 'rencana', 'plan', 'harapan', 'expectation'];
        foreach ($conversationHistory as $msg) {
            if (isset($msg['content']) && $msg['role'] === 'user') {
                foreach ($goalKeywords as $keyword) {
                    if (stripos($msg['content'], $keyword) !== false) {
                        $context['user_goals'][] = $msg['content'];
                        break;
                    }
                }
            }
        }

        return $context;
    }

    /**
     * Build a summary of conversation context for AI memory
     */
    private function buildContextSummary(array $conversationContext, string $language = 'id'): string
    {
        $summary = [];
        
        if ($language === 'en') {
            if (!empty($conversationContext['topics'])) {
                $summary[] = "Topics discussed: " . implode(', ', array_unique($conversationContext['topics']));
            }
            
            if ($conversationContext['budget_mentioned']) {
                $summary[] = "User has mentioned budget/financial information";
            }
            
            if ($conversationContext['timeframe_mentioned']) {
                $summary[] = "User has mentioned timeframe/deadline";
            }
            
            if ($conversationContext['location_mentioned']) {
                $summary[] = "User has mentioned specific locations";
            }
            
            if ($conversationContext['business_type']) {
                $summary[] = "Business type mentioned: " . $conversationContext['business_type'];
            }
            
            if ($conversationContext['investment_type']) {
                $summary[] = "Investment type mentioned: " . $conversationContext['investment_type'];
            }
            
            if ($conversationContext['risk_profile']) {
                $summary[] = "Risk profile: " . $conversationContext['risk_profile'];
            }
            
            if (!empty($conversationContext['user_goals'])) {
                $summary[] = "User goals mentioned: " . implode('; ', array_slice($conversationContext['user_goals'], 0, 3));
            }
        } else {
            if (!empty($conversationContext['topics'])) {
                $summary[] = "Topik yang dibahas: " . implode(', ', array_unique($conversationContext['topics']));
            }
            
            if ($conversationContext['budget_mentioned']) {
                $summary[] = "User sudah menyebutkan budget/informasi finansial";
            }
            
            if ($conversationContext['timeframe_mentioned']) {
                $summary[] = "User sudah menyebutkan timeframe/deadline";
            }
            
            if ($conversationContext['location_mentioned']) {
                $summary[] = "User sudah menyebutkan lokasi spesifik";
            }
            
            if ($conversationContext['business_type']) {
                $summary[] = "Jenis usaha yang disebutkan: " . $conversationContext['business_type'];
            }
            
            if ($conversationContext['investment_type']) {
                $summary[] = "Jenis investasi yang disebutkan: " . $conversationContext['investment_type'];
            }
            
            if ($conversationContext['risk_profile']) {
                $summary[] = "Profil risiko: " . $conversationContext['risk_profile'];
            }
            
            if (!empty($conversationContext['user_goals'])) {
                $summary[] = "Tujuan user yang disebutkan: " . implode('; ', array_slice($conversationContext['user_goals'], 0, 3));
            }
        }
        
        return implode('. ', $summary);
    }

    /**
     * Generate local recommendations with conversation context
     */
    private function generateLocalRecommendationsWithContext(string $userMessage, string $language = 'id', array $conversationHistory = []): array
    {
        // Combine only for auxiliary signals (budget), not for city filtering
        $fullContext = $userMessage;
        foreach ($conversationHistory as $msg) {
            if (isset($msg['content']) && $msg['role'] === 'user') {
                $fullContext .= ' ' . $msg['content'];
            }
        }

        // Gunakan budget dari pesan terbaru sebagai prioritas
        $latestBudgetInfo = $this->extractBudgetInfo($userMessage);
        $forcedBudget = $latestBudgetInfo['value'] ?? null;
        $forcedDirection = $latestBudgetInfo['direction'] ?? null;

        // Use latest message for city detection; keep budget from latest
        return $this->generateLocalRecommendations($userMessage, $language, $forcedBudget, $forcedDirection);
    }

    /**
     * Check if user wants to see recommendations
     */
    private function userWantsRecommendations(string $userMessage, array $conversationHistory): bool
    {
        $lower = mb_strtolower($userMessage, 'UTF-8');
        
        // Check for explicit request for LOCATION LINKS
        $explicitLinkWords = [
            'tampilkan link', 'show link', 'kasih link', 'berikan link',
            'tampilkan tempat', 'show places', 'kasih tempat', 'berikan tempat',
            'tampilkan lokasi', 'show location', 'kasih lokasi', 'berikan lokasi',
            'tampilkan semua', 'show all', 'kasih semua', 'berikan semua',
            'link nya', 'linknya', 'tautan', 'link', 'iya boleh', 'boleh', 'mau',
            'rekomendasi', 'recommendation', 'pilihan', 'option', 'iya', 'ya'
        ];
        
        foreach ($explicitLinkWords as $word) {
            if (str_contains($lower, $word)) {
                return true;
            }
        }
        
        // Check conversation history for previous context
        $fullConversation = $userMessage;
        foreach ($conversationHistory as $msg) {
            if (isset($msg['content'])) {
                $fullConversation .= ' ' . $msg['content'];
            }
        }
        
        // Check if user has provided basic information (city + budget OR size)
        $hasBasicInfo = $this->hasBasicBusinessInfo($fullConversation);
        
        // Show recommendations if user has basic info and asks for them
        return $hasBasicInfo && $this->hasExplicitConfirmation($lower);
    }

    /**
     * Check if user has provided complete business information
     */
    private function hasCompleteBusinessInfo(string $conversation): bool
    {
        $lower = mb_strtolower($conversation, 'UTF-8');
        
        // Check for city
        $hasCity = preg_match('/\b(jakarta|bogor|depok|tangerang|bandung|bengkulu|jember|banjarmasin|cilacap|ciamis|serang|bsd city|kuningan|cilincing|ancol|wanareja|bojongmengger|medan|palembang|denpasar|yogyakarta|solo|malang|surabaya|manado|bekasi|pekanbaru|padang|semarang|makassar|balikpapan)\b/u', $lower);
        
        // Check for budget
        $hasBudget = preg_match('/\b(\d+[\.,]?\d*\s*(juta|jt|ribu|rb)|\d{5,9})\b/u', $lower);
        
        // Check for size
        $hasSize = preg_match('/\b(\d+\s*x\s*\d+|m2|m²|\d+\s*meter|\d+\s*m)\b/u', $lower);
        
        // Check for business type
        $hasBusinessType = preg_match('/\b(toko|restoran|warung|cafe|bengkel|cuci|apotek|kantor|retail|roti|makan|kelontong|pakaian|fashion)\b/u', $lower);
        
        // Need at least 3 out of 4 criteria
        $criteria = [$hasCity, $hasBudget, $hasSize, $hasBusinessType];
        $metCriteria = array_sum($criteria);
        
        return $metCriteria >= 3;
    }

    /**
     * Check if user has provided basic business information (more flexible)
     */
    private function hasBasicBusinessInfo(string $conversation): bool
    {
        $lower = mb_strtolower($conversation, 'UTF-8');
        
        // Check for city
        $hasCity = preg_match('/\b(jakarta|bogor|depok|tangerang|bandung|bengkulu|jember|banjarmasin|cilacap|ciamis|serang|bsd city|kuningan|cilincing|ancol|wanareja|bojongmengger|medan|palembang|denpasar|yogyakarta|solo|malang|surabaya|manado|bekasi|pekanbaru|padang|semarang|makassar|balikpapan)\b/u', $lower);
        
        // Check for budget OR size
        $hasBudget = preg_match('/\b(\d+[\.,]?\d*\s*(juta|jt|ribu|rb)|\d{5,9})\b/u', $lower);
        $hasSize = preg_match('/\b(\d+\s*x\s*\d+|m2|m²|\d+\s*meter|\d+\s*m)\b/u', $lower);
        
        // Need at least city + (budget OR size)
        return $hasCity && ($hasBudget || $hasSize);
    }

    /**
     * Check for explicit confirmation
     */
    private function hasExplicitConfirmation(string $message): bool
    {
        $explicitWords = [
            'kasih', 'berikan', 'tampilkan', 'tunjukan', 'lihat', 'rekomendasi',
            'pilihan', 'option', 'cari', 'temukan', 'find', 'show', 'iya', 'boleh',
            'mau', 'ya', 'yes', 'ok', 'oke', 'link', 'tautan', 'tampilkan lokasi',
            'kasih lokasi', 'berikan lokasi', 'tampilkan tempat', 'kasih tempat'
        ];
        
        foreach ($explicitWords as $word) {
            if (str_contains($message, $word)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Deteksi jenis usaha dari pesan user
     */
    private function detectBusinessType(string $message): string
    {
        $lower = mb_strtolower($message, 'UTF-8');
        
        $businessTypes = [
            'investasi emas' => ['emas', 'gold', 'logam mulia', 'precious metal', 'investasi emas'],
            'investasi properti' => ['properti', 'property', 'rumah', 'tanah', 'real estate', 'investasi properti'],
            'investasi saham' => ['saham', 'stock', 'bursa', 'trading', 'investasi saham'],
            'investasi crypto' => ['crypto', 'bitcoin', 'ethereum', 'cryptocurrency', 'mata uang digital', 'investasi crypto'],
            'investasi reksadana' => ['reksadana', 'mutual fund', 'investasi reksadana'],
            'investasi obligasi' => ['obligasi', 'bond', 'investasi obligasi'],
            'toko roti' => ['toko roti', 'bakery', 'roti', 'kue', 'pastry'],
            'toko bunga' => ['toko bunga', 'florist', 'bunga', 'karangan bunga', 'bouquet'],
            'restoran' => ['restoran', 'restaurant', 'warung makan', 'cafe', 'kafe'],
            'toko kelontong' => ['toko kelontong', 'warung', 'minimarket', 'toko'],
            'bengkel' => ['bengkel', 'service', 'servis', 'mekanik'],
            'cuci mobil' => ['cuci mobil', 'car wash', 'autocare'],
            'toko pakaian' => ['toko pakaian', 'fashion', 'baju', 'pakaian'],
            'apotek' => ['apotek', 'pharmacy', 'obat', 'farmasi'],
            'kantor' => ['kantor', 'office', 'perkantoran'],
            'retail' => ['retail', 'toko', 'store', 'toko retail']
        ];
        
        foreach ($businessTypes as $type => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($lower, $keyword)) {
                    return $type;
                }
            }
        }
        
        return 'umum';
    }

    /**
     * Analisis potensi lokasi berdasarkan jenis usaha dan budget
     */
    private function analyzeLocationPotential(array $location, string $businessType, ?float $budget, string $language): array
    {
        $score = 0;
        $insights = [];
        $recommendations = [];
        $budgetReasons = [];
        
        // Analisis harga
        if ($location['min_price'] !== null && $budget !== null) {
            $priceRatio = $location['min_price'] / $budget;
            if ($priceRatio <= 0.8) {
                $score += 30;
                $insights[] = 'Harga sangat kompetitif';
                $budgetReasons[] = $language === 'en' ? 'price is well within your budget' : 'harga berada jauh di bawah budget Anda';
            } elseif ($priceRatio <= 1.0) {
                $score += 20;
                $insights[] = 'Harga sesuai budget';
                $budgetReasons[] = $language === 'en' ? 'price matches your budget' : 'harga sesuai dengan budget Anda';
            } elseif ($priceRatio <= 1.2) {
                $score += 10;
                $insights[] = 'Harga sedikit di atas budget';
                $budgetReasons[] = $language === 'en' ? 'slightly above but close to your budget' : 'sedikit di atas namun masih dekat dengan budget Anda';
            } else {
                $score -= 10;
                $insights[] = 'Harga di atas budget';
                $budgetReasons[] = $language === 'en' ? 'above your budget range' : 'di atas kisaran budget Anda';
            }
        }
        
        // Analisis ukuran ruang
        if (!empty($location['sizes'])) {
            $avgSize = array_sum($location['sizes']) / count($location['sizes']);
            $minSize = min($location['sizes']);
            $maxSize = max($location['sizes']);
            
            // Analisis berdasarkan jenis usaha
            switch ($businessType) {
                case 'toko roti':
                    if ($minSize >= 10 && $minSize <= 30) {
                        $score += 25;
                        $insights[] = 'Ukuran ideal untuk toko roti';
                    } elseif ($minSize < 10) {
                        $score += 10;
                        $insights[] = 'Ukuran kecil, cocok untuk toko roti sederhana';
                    } else {
                        $score += 15;
                        $insights[] = 'Ukuran besar, bisa untuk toko roti + produksi';
                    }
                    break;
                    
                case 'restoran':
                    if ($minSize >= 20 && $minSize <= 50) {
                        $score += 25;
                        $insights[] = 'Ukuran ideal untuk restoran';
                    } elseif ($minSize < 20) {
                        $score += 10;
                        $insights[] = 'Ukuran kecil, cocok untuk warung makan';
                    } else {
                        $score += 15;
                        $insights[] = 'Ukuran besar, cocok untuk restoran besar';
                    }
                    break;
                    
                case 'toko kelontong':
                    if ($minSize >= 15 && $minSize <= 40) {
                        $score += 25;
                        $insights[] = 'Ukuran ideal untuk toko kelontong';
                    } else {
                        $score += 15;
                        $insights[] = 'Ukuran fleksibel untuk toko kelontong';
                    }
                    break;
                    
                default:
                    if ($minSize >= 10) {
                        $score += 20;
                        $insights[] = 'Ukuran memadai untuk usaha';
                    }
                    break;
            }
        }
        
        // Analisis lokasi berdasarkan kota
        $city = $location['city'] ?? '';
        switch (mb_strtolower($city, 'UTF-8')) {
            case 'jakarta':
                $score += 20;
                $insights[] = 'Lokasi strategis di Jakarta dengan aksesibilitas tinggi';
                $recommendations[] = 'Ideal untuk usaha yang mengandalkan arus kendaraan tinggi';
                break;
            case 'depok':
                $score += 15;
                $insights[] = 'Lokasi di Depok dengan potensi pasar residensial';
                $recommendations[] = 'Cocok untuk usaha yang melayani komunitas lokal';
                break;
            case 'tangerang':
                $score += 15;
                $insights[] = 'Lokasi di Tangerang dengan akses ke Jakarta';
                $recommendations[] = 'Strategis untuk usaha yang melayani area Jabodetabek';
                break;
            case 'bogor':
                $score += 12;
                $insights[] = 'Lokasi di Bogor dengan karakteristik pasar unik';
                $recommendations[] = 'Cocok untuk usaha yang memahami karakteristik Bogor';
                break;
            default:
                $score += 10;
                $insights[] = 'Lokasi di ' . $city . ' dengan potensi pasar lokal';
                break;
        }
        
        // Analisis berdasarkan alamat
        $address = $location['address'] ?? '';
        if (str_contains(mb_strtolower($address, 'UTF-8'), 'spbu')) {
            $score += 15;
            $insights[] = 'Lokasi di area SPBU dengan arus kendaraan stabil';
            $recommendations[] = 'Ideal untuk usaha yang mengandalkan pengunjung SPBU';
        }
        
        // Analisis fasilitas berdasarkan nama space
        $facilities = [];
        foreach ($location['spaces'] ?? [] as $space) {
            $spaceName = $space['name'] ?? '';
            if (str_contains(mb_strtolower($spaceName, 'UTF-8'), 'atm')) {
                $facilities[] = 'ATM';
            }
            if (str_contains(mb_strtolower($spaceName, 'UTF-8'), 'parkir')) {
                $facilities[] = 'Parkir';
            }
        }
        
        if (!empty($facilities)) {
            $score += 10;
            $insights[] = 'Tersedia fasilitas: ' . implode(', ', $facilities);
        }
        
        // Rekomendasi berdasarkan analisis
        if ($score >= 70) {
            $recommendations[] = 'Lokasi sangat direkomendasikan untuk ' . $businessType;
        } elseif ($score >= 50) {
            $recommendations[] = 'Lokasi cukup baik untuk ' . $businessType;
        } else {
            $recommendations[] = 'Lokasi memerlukan pertimbangan lebih lanjut';
        }
        
        return [
            'score' => max(0, min(100, $score)),
            'insights' => $insights,
            'recommendations' => $recommendations,
            'business_type' => $businessType,
            'price_analysis' => $location['min_price'] !== null ? $this->analyzePrice($location['min_price'], $budget) : null,
            'budget_reasons' => $budgetReasons
        ];
    }

    /**
     * Analisis harga
     */
    private function analyzePrice(float $price, ?float $budget): array
    {
        if ($budget === null) {
            return ['status' => 'unknown', 'message' => 'Budget tidak diketahui'];
        }
        
        $ratio = $price / $budget;
        
        if ($ratio <= 0.8) {
            return ['status' => 'excellent', 'message' => 'Harga sangat kompetitif'];
        } elseif ($ratio <= 1.0) {
            return ['status' => 'good', 'message' => 'Harga sesuai budget'];
        } elseif ($ratio <= 1.2) {
            return ['status' => 'fair', 'message' => 'Harga sedikit di atas budget'];
        } else {
            return ['status' => 'expensive', 'message' => 'Harga di atas budget'];
        }
    }

    /**
     * Generate detailed location description
     */
    private function generateLocationDescription(array $location, string $language = 'id'): string
    {
        $name = $location['name'] ?? 'Lokasi';
        $city = $location['city'] ?? '';
        $address = $location['address'] ?? '';
        $price = $location['price'] ?? null;
        $priceType = $location['price_type'] ?? 'm2';
        $analysis = $location['analysis'] ?? [];
        
        if ($language === 'en') {
            $description = "**{$name}** - {$city}\n";
            $description .= "📍 Address: {$address}\n";
            
            if ($price !== null) {
                $priceLabel = 'IDR ' . number_format($price, 0, ',', '.') . ($priceType === 'm2' ? ' / m² / Month' : ' / Month');
                $description .= "💰 Price: {$priceLabel}\n";
            }
            
            if (!empty($analysis['insights'])) {
                $description .= "📊 Analysis: " . implode(', ', $analysis['insights']) . "\n";
            }
            
            if (!empty($analysis['recommendations'])) {
                $description .= "💡 Recommendations: " . implode(', ', $analysis['recommendations']);
            }
        } else {
            $description = "**{$name}** - {$city}\n";
            $description .= "📍 Alamat: {$address}\n";
            
            if ($price !== null) {
                $priceLabel = 'IDR ' . number_format($price, 0, ',', '.') . ($priceType === 'm2' ? ' / m² / Bulan' : ' / Bulan');
                $description .= "💰 Harga: {$priceLabel}\n";
            }
            
            if (!empty($analysis['insights'])) {
                $description .= "📊 Analisis: " . implode(', ', $analysis['insights']) . "\n";
            }
            
            if (!empty($analysis['recommendations'])) {
                $description .= "💡 Rekomendasi: " . implode(', ', $analysis['recommendations']);
            }
        }
        
        return $description;
    }

    /**
     * Check if user wants database analysis (without links)
     */
    private function userWantsDatabaseAnalysis(string $userMessage, array $conversationHistory): bool
    {
        $lower = mb_strtolower($userMessage, 'UTF-8');
        
        // Check for consultation/analysis words
        $analysisWords = [
            'jelaskan', 'explain', 'analisis', 'analysis', 'terperinci', 'detailed',
            'konsultasi', 'consultation', 'saran', 'advice', 'rekomendasi', 'recommendation',
            'bagaimana', 'how', 'gimana', 'apa yang', 'what', 'bisa', 'can',
            'tempat', 'place', 'lokasi', 'location', 'daerah', 'area'
        ];
        
        foreach ($analysisWords as $word) {
            if (str_contains($lower, $word)) {
                return true;
            }
        }
        
        // Check conversation history for context
        $fullConversation = $userMessage;
        foreach ($conversationHistory as $msg) {
            if (isset($msg['content'])) {
                $fullConversation .= ' ' . $msg['content'];
            }
        }
        
        // Check if user has mentioned a city (for location analysis)
        $knownCities = ['jakarta', 'bogor', 'depok', 'tangerang', 'bandung', 'bengkulu', 'jember', 'banjarmasin', 'cilacap', 'ciamis', 'serang', 'bsd city', 'kuningan', 'cilincing', 'ancol', 'wanareja', 'bojongmengger', 'medan', 'palembang', 'denpasar', 'yogyakarta', 'solo', 'malang', 'surabaya', 'manado', 'bekasi', 'pekanbaru', 'padang', 'semarang', 'makassar', 'balikpapan'];
        foreach ($knownCities as $city) {
            if (str_contains(mb_strtolower($fullConversation, 'UTF-8'), $city)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Load all location data from external APIs
     */
    private function loadAllLocationData(): array
    {
        try {
            // Use ExternalApiService to fetch data from APIs
            return $this->externalApiService->getAllLocationData();
        } catch (\Exception $e) {
            Log::error('Failed to load location data from external APIs', [
                'message' => $e->getMessage()
            ]);
            
            // Fallback to local JSON files if API fails
            return $this->loadAllLocationDataFromFiles();
        }
    }

    /**
     * Fallback method to load data from local JSON files
     */
    private function loadAllLocationDataFromFiles(): array
    {
        $allLocations = [];
        
        // Load listings_export.json (Listings data)
        $listingsPath = base_path('resources/views/data/listings_export.json');
        if (file_exists($listingsPath)) {
            $listingsData = json_decode(file_get_contents($listingsPath), true);
            if (is_array($listingsData) && isset($listingsData['listings'])) {
                $transformedListings = $this->transformListingsData($listingsData['listings']);
                $allLocations = array_merge($allLocations, $transformedListings);
            }
        }
        
        // Load spaces_export.json (Spaces data)
        $spacesPath = base_path('resources/views/data/spaces_export.json');
        if (file_exists($spacesPath)) {
            $spacesData = json_decode(file_get_contents($spacesPath), true);
            if (is_array($spacesData) && isset($spacesData['spaces'])) {
                $transformedSpaces = $this->transformSpacesData($spacesData['spaces']);
                $allLocations = array_merge($allLocations, $transformedSpaces);
            }
        }
        
        return $allLocations;
    }

    /**
     * Transform listings data to match our internal format
     */
    private function transformListingsData(array $listings): array
    {
        $transformedLocations = [];
        
        foreach ($listings as $listing) {
            $transformedLocations[] = [
                'id' => (string) ($listing['id'] ?? ''),
                'name' => $listing['name'] ?? 'Unknown Listing',
                'address' => $listing['address'] ?? '',
                'city' => $this->extractCityFromAddress($listing['address'] ?? ''),
                'cover' => $this->getFirstMediaUrl($listing['media'] ?? []),
                'spaces' => [
                    [
                        'name' => $listing['name'] ?? 'Space',
                        'price' => null, // No price info in listings data
                        'price_type' => 'lot',
                        'space_size' => null,
                        'description' => '',
                        'source' => $listing['source'] ?? 'pms',
                        'spaces_count' => $listing['spaces'] ?? 0,
                        'city_name' => $listing['city_name'] ?? '',
                        'area_name' => $listing['area_name'] ?? ''
                    ]
                ]
            ];
        }
        
        return $transformedLocations;
    }

    /**
     * Transform spaces data to match our internal format
     */
    private function transformSpacesData(array $spaces): array
    {
        $groupedByListing = [];
        
        foreach ($spaces as $space) {
            $listingId = (string) ($space['listing_id'] ?? 'unknown');
            
            if (!isset($groupedByListing[$listingId])) {
                $groupedByListing[$listingId] = [
                    'id' => $listingId,
                    'name' => 'Space Listing ' . $listingId,
                    'address' => $space['listing_address'] ?? '',
                    'city' => $this->extractCityFromAddress($space['listing_address'] ?? ''),
                    'cover' => $this->getFirstMediaUrl($space['media'] ?? []),
                    'spaces' => []
                ];
            }
            
            $groupedByListing[$listingId]['spaces'][] = [
                'name' => $space['name'] ?? ($space['code'] ?? 'Space'),
                'price' => isset($space['price']) ? (float) $space['price'] : null,
                'price_type' => $space['price_type'] ?? 'm2',
                'space_size' => isset($space['size_sqm']) ? (float) $space['size_sqm'] : null,
                'description' => '',
                'source' => $space['source'] ?? 'private_sector',
                'type' => $space['type'] ?? '',
                'min_period' => $space['min_period'] ?? '',
                'code' => $space['code'] ?? ''
            ];
        }
        
        return array_values($groupedByListing);
    }

    /**
     * Get the first media URL from media array
     */
    private function getFirstMediaUrl(array $media): ?string
    {
        if (empty($media) || !is_array($media)) {
            return null;
        }
        
        $firstMedia = reset($media);
        return $firstMedia['url'] ?? null;
    }
    private function transformExternalPropertyData(array $externalData): array
    {
        $transformedLocations = [];
        
        // Process Google properties
        if (isset($externalData['google']) && is_array($externalData['google'])) {
            foreach ($externalData['google'] as $index => $property) {
                $transformedLocations[] = $this->transformGoogleProperty($property, $index);
            }
        }
        
        // Process Instagram properties
        if (isset($externalData['instagram']) && is_array($externalData['instagram'])) {
            foreach ($externalData['instagram'] as $index => $property) {
                $transformedLocations[] = $this->transformInstagramProperty($property, $index);
            }
        }
        
        // Process Twitter properties
        if (isset($externalData['twitter']) && is_array($externalData['twitter'])) {
            foreach ($externalData['twitter'] as $index => $property) {
                $transformedLocations[] = $this->transformTwitterProperty($property, $index);
            }
        }
        
        // Process Facebook properties
        if (isset($externalData['facebook']) && is_array($externalData['facebook'])) {
            foreach ($externalData['facebook'] as $index => $property) {
                $transformedLocations[] = $this->transformFacebookProperty($property, $index);
            }
        }
        
        return $transformedLocations;
    }

    /**
     * Transform SPBU merged data (merged_spaces_listings_picsum.json) to internal format
     * Input items are individual spaces with nested listing info; we group them by listing
     */
    private function transformSpbuData(array $items): array
    {
        $groupedByListing = [];

        foreach ($items as $item) {
            $listing = $item['listing'] ?? null;
            if (!$listing || !isset($listing['id'])) {
                // Skip entries without proper listing info
                continue;
            }

            $listingId = (string) $listing['id'];
            if (!isset($groupedByListing[$listingId])) {
                $groupedByListing[$listingId] = [
                    'id' => $listingId,
                    'name' => $listing['name'] ?? 'SPBU ' . $listingId,
                    'address' => $listing['address'] ?? '',
                    'city' => $this->extractCityFromAddress($listing['address'] ?? ''),
                    'cover' => $item['cover'] ?? null,
                    'spaces' => []
                ];
            }

            $groupedByListing[$listingId]['spaces'][] = [
                'name' => $item['name'] ?? ($item['code'] ?? ('Space ' . ($item['id'] ?? ''))),
                'price' => isset($item['price']) ? (float) $item['price'] : null,
                // Map price_type: 'fix' -> 'lot', keep others if already aligned
                'price_type' => (isset($item['price_type']) && strtolower((string)$item['price_type']) === 'fix') ? 'lot' : ($item['price_type'] ?? 'm2'),
                'space_size' => isset($item['size_sqm']) ? (float) $item['size_sqm'] : null,
                'description' => $item['description'] ?? '',
                'source' => 'private_sector',
            ];
        }

        return array_values($groupedByListing);
    }

    /**
     * Transform Google property data
     */
    private function transformGoogleProperty(array $property, int $index): array
    {
        $city = $property['city'] ?? 'Unknown';
        $price = $this->extractPriceFromString($property['price'] ?? '');
        $area = $this->extractAreaFromString($property['area'] ?? '');
        
        return [
            'id' => 'google_' . $index,
            'name' => $property['title'] ?? 'Property',
            'address' => $property['location'] ?? '',
            'city' => $city,
            'cover' => $property['image'] ?? null,
            'spaces' => [
                [
                    'name' => $property['title'] ?? 'Space',
                    'price' => $price,
                    'price_type' => 'lot',
                    'space_size' => $area,
                    'description' => $property['description'] ?? '',
                    'bedrooms' => $property['bedrooms'] ?? 0,
                    'bathrooms' => $property['bathrooms'] ?? 0,
                    'source' => 'google',
                    'platform' => $property['platform'] ?? 'google',
                    'link' => $property['link'] ?? '',
                    'type' => $property['type'] ?? 'sale'
                ]
            ]
        ];
    }

    /**
     * Transform Instagram property data
     */
    private function transformInstagramProperty(array $property, int $index): array
    {
        $city = $property['location'] ?? 'Unknown';
        $price = $this->extractPriceFromString($property['price'] ?? '');
        $caption = (string) ($property['caption'] ?? '');
        $title = $this->generateHumanFriendlyTitleFromText($caption, $city);

        return [
            'id' => 'instagram_' . $index,
            'name' => $title,
            'address' => $city,
            'city' => $city,
            'cover' => $property['image'] ?? null,
            'spaces' => [
                [
                    'name' => $title,
                    'price' => $price,
                    'price_type' => 'lot',
                    'space_size' => null,
                    'description' => $caption,
                    'source' => 'instagram',
                    'platform' => 'instagram',
                    'link' => $property['link'] ?? '',
                    'author' => $property['author'] ?? '',
                    'likes' => $property['likes'] ?? 0,
                    'comments' => $property['comments'] ?? 0
                ]
            ]
        ];
    }

    /**
     * Transform Twitter property data
     */
    private function transformTwitterProperty(array $property, int $index): array
    {
        $city = $property['location'] ?? 'Unknown';
        $price = $this->extractPriceFromString($property['price'] ?? '');
        $text = (string) ($property['text'] ?? '');
        $title = $this->generateHumanFriendlyTitleFromText($text, $city);

        return [
            'id' => 'twitter_' . $index,
            'name' => $title,
            'address' => $city,
            'city' => $city,
            'cover' => null,
            'spaces' => [
                [
                    'name' => $title,
                    'price' => $price,
                    'price_type' => 'lot',
                    'space_size' => null,
                    'description' => $text,
                    'source' => 'twitter',
                    'platform' => 'twitter',
                    'link' => $property['link'] ?? '',
                    'author' => $property['author'] ?? '',
                    'retweets' => $property['retweets'] ?? 0,
                    'likes' => $property['likes'] ?? 0
                ]
            ]
        ];
    }

    /**
     * Generate a human-friendly title from free-form text (e.g., tweet) and optional city
     */
    private function generateHumanFriendlyTitleFromText(string $text, ?string $city = null): string
    {
        $base = 'Properti';
        $lower = mb_strtolower($text, 'UTF-8');

        // Determine property type from keywords
        $type = null;
        $typeMap = [
            'villa' => 'Villa',
            'apartemen' => 'Apartemen', 'apartment' => 'Apartemen', 'apartement' => 'Apartemen',
            'ruko' => 'Ruko', 'shop' => 'Ruko',
            'gudang' => 'Gudang', 'warehouse' => 'Gudang',
            'tanah' => 'Tanah', 'land' => 'Tanah',
            'kantor' => 'Kantor', 'office' => 'Kantor',
            'gedung' => 'Gedung', 'building' => 'Gedung',
            'rumah' => 'Rumah', 'house' => 'Rumah',
            'kos' => 'Kos', 'kost' => 'Kos', 'boarding' => 'Kos'
        ];
        foreach ($typeMap as $needle => $label) {
            if (str_contains($lower, $needle)) { $type = $label; break; }
        }
        if (!$type) { $type = 'Properti'; }

        // Detect BR count (e.g., 2BR, 3 BR)
        $brLabel = '';
        if (preg_match('/(\d+)\s*br/i', $text, $m)) {
            $brLabel = ((int)$m[1]) . 'BR';
        }

        // Compose title
        $parts = [];
        if ($type !== 'Properti') { $parts[] = $type; } else { $parts[] = $base; }
        if ($brLabel !== '') { $parts[] = $brLabel; }
        $title = trim(implode(' ', $parts));

        // Append city if present and not Unknown
        if (!empty($city) && mb_strtolower($city, 'UTF-8') !== 'unknown') {
            $title .= ' di ' . $city;
        }

        // Fallback: if text is short and no type/BR detected, use trimmed text
        if ($title === $base && !empty(trim($text))) {
            $snippet = trim(mb_substr(preg_replace('/\s+/', ' ', $text), 0, 60));
            $title = $snippet;
        }

        return $title;
    }

    /**
     * Transform Facebook property data
     */
    private function transformFacebookProperty(array $property, int $index): array
    {
        $city = $property['location'] ?? 'Unknown';
        $price = $this->extractPriceFromString($property['price'] ?? '');
        
        return [
            'id' => 'facebook_' . $index,
            'name' => $property['name'] ?? 'Facebook Property',
            'address' => $city,
            'city' => $city,
            'cover' => null,
            'spaces' => [
                [
                    'name' => 'Facebook Space',
                    'price' => $price,
                    'price_type' => 'lot',
                    'space_size' => null,
                    'description' => $property['description'] ?? '',
                    'source' => 'facebook',
                    'platform' => 'facebook',
                    'link' => $property['groupUrl'] ?? '',
                    'memberCount' => $property['memberCount'] ?? '',
                    'posts' => $property['posts'] ?? []
                ]
            ]
        ];
    }

    /**
     * Extract numeric price from price string
     */
    private function extractPriceFromString(string $priceString): ?float
    {
        $lower = strtolower($priceString);

        // Jika ada kata satuan, ambil angka lalu kalikan satuannya
        if (preg_match('/(\d+[\.,]?\d*)\s*(miliar)/i', $priceString, $m)) {
            $num = floatval(str_replace([',','.'], '', $m[1]));
            return $num * 1000000000;
        }
        if (preg_match('/(\d+[\.,]?\d*)\s*(juta|jt)/i', $priceString, $m)) {
            $num = floatval(str_replace([',','.'], '', $m[1]));
            return $num * 1000000;
        }
        if (preg_match('/(\d+[\.,]?\d*)\s*(ribu|rb)/i', $priceString, $m)) {
            $num = floatval(str_replace([',','.'], '', $m[1]));
            return $num * 1000;
        }

        // Tanpa kata satuan: buang semua non-digit agar "4.460.000.000" menjadi 4460000000
        $digitsOnly = preg_replace('/\D+/', '', $priceString);
        if (!empty($digitsOnly)) {
            return (float) $digitsOnly;
        }

        return null;
    }

    /**
     * Extract area from area string
     */
    private function extractAreaFromString(string $areaString): ?float
    {
        // Extract numbers from area string like "176 m²" or "195 m²"
        if (preg_match('/(\d+(?:\.\d+)?)\s*m²?/', $areaString, $matches)) {
            return floatval($matches[1]);
        }
        
        return null;
    }

    /**
     * Generate database analysis without showing links
     */
    private function generateDatabaseAnalysis(string $userMessage, string $language = 'id', array $conversationHistory = []): array
    {
        try {
            $locations = $this->loadAllLocationData();

            $lower = mb_strtolower($userMessage, 'UTF-8');
            $knownCities = ['jakarta','bogor','depok','tangerang','bandung','bengkulu','jember','banjarmasin','cilacap','ciamis','serang','bsd city','kuningan','cilincing','ancol','wanareja','bojongmengger','medan','palembang','denpasar','yogyakarta','solo','malang','surabaya','manado','bekasi','pekanbaru','padang','semarang','makassar','balikpapan'];
            $city = null;
            foreach ($knownCities as $c) {
                if (str_contains($lower, $c)) { 
                    $city = ucfirst($c); 
                    break; 
                }
            }

            // Ambil info budget (nilai + arah: minimal/maksimal)
            $budgetInfo = $this->extractBudgetInfo($userMessage);
            $budget = $budgetInfo['value'];

            // Deteksi jenis usaha dari pesan
            $businessType = $this->detectBusinessType($userMessage);

            // Analisis data berdasarkan kriteria
            $analysis = $this->analyzeDatabaseData($locations, $city, $budget, $businessType, $language);
            
            return $analysis;
        } catch (\Throwable $e) {
            Log::warning('Database analysis error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Build structured data context for AI grounding
     */
    private function buildDataContext(string $userMessage, array $allLocations, ?float $budgetValue, ?string $budgetDirection, string $businessType): array
    {
        $mentionedCities = $this->getMentionedCities($userMessage);

        // Filter and normalize minimal fields to keep payload compact
        $filtered = [];
        $areaKeywords = [];
        // Extract potential area keyword from the message beyond city (e.g., Kemang, Kuningan, Senopati)
        if (preg_match('/\b(kemang|kuningan|senopati|ancol|cilincing|margonda|bsd|jaksel|jakbar|jaktim|jakut|jakpus)\b/i', $userMessage, $am)) {
            $areaKeywords[] = strtolower($am[1]);
        }
        foreach ($allLocations as $loc) {
            $address = $loc['address'] ?? '';
            $locationCity = $this->extractCityFromAddress($address);
            if (!empty($mentionedCities)) {
                $isAllowed = false;
                foreach ($mentionedCities as $mc) {
                    if (mb_strtolower($locationCity, 'UTF-8') === mb_strtolower($mc, 'UTF-8')) { $isAllowed = true; break; }
                }
                if (!$isAllowed) { continue; }
            }

            // If specific area keyword provided, ensure address/name contains it
            if (!empty($areaKeywords)) {
                $hay = mb_strtolower(($loc['name'] ?? '') . ' ' . $address, 'UTF-8');
                $matchedArea = false;
                foreach ($areaKeywords as $kw) {
                    if (str_contains($hay, $kw)) { $matchedArea = true; break; }
                }
                if (!$matchedArea) { continue; }
            }

            // Collect min price and first space name
            $minPrice = null; $priceType = 'm2';
            $firstSpaceName = null;
            foreach ($loc['spaces'] ?? [] as $space) {
                if ($firstSpaceName === null && !empty($space['name'])) {
                    $firstSpaceName = $space['name'];
                }
                if (isset($space['price'])) {
                    $p = (float)$space['price'];
                    if ($minPrice === null || $p < $minPrice) { $minPrice = $p; $priceType = $space['price_type'] ?? 'm2'; }
                }
            }

            // Budget gate if provided
            if ($budgetValue !== null && $minPrice !== null) {
                $tolerance = 0.05;
                if ($budgetDirection === 'min' && $minPrice < $budgetValue) {
                    continue;
                }
                if (($budgetDirection === 'max' || $budgetDirection === null) && $minPrice > $budgetValue * (1 + $tolerance)) {
                    continue;
                }
            }

            $filtered[] = [
                'id' => $loc['id'] ?? null,
                'title' => $loc['name'] ?? $firstSpaceName ?? 'Lokasi',
                'address' => $address,
                'city' => $locationCity,
                'min_price' => $minPrice,
                'price_type' => $priceType,
                'cover' => $loc['cover'] ?? null,
                'link' => '/detail-spbu/' . ($loc['id'] ?? ''),
            ];
        }

        // Limit to keep token usage reasonable
        $filtered = array_slice($filtered, 0, 20);

        return [
            'query_city' => implode(', ', $mentionedCities),
            'query_budget' => $budgetValue,
            'query_budget_direction' => $budgetDirection,
            'business_type' => $businessType,
            'locations' => $filtered,
        ];
    }

    /**
     * Analyze database data and provide insights
     */
    private function analyzeDatabaseData(array $locations, ?string $city, ?float $budget, string $businessType, string $language = 'id'): array
    {
        $analysis = [
            'total_locations' => 0,
            'city_breakdown' => [],
            'price_analysis' => [],
            'business_insights' => [],
            'recommendations' => [],
            'area_insights' => []
        ];

        // Filter locations by city if specified
        $filteredLocations = $locations;
        if ($city) {
            $filteredLocations = array_filter($locations, function($loc) use ($city) {
                $address = $loc['address'] ?? '';
                $locationCity = $this->extractCityFromAddress($address);
                return mb_strtolower($locationCity, 'UTF-8') === mb_strtolower($city, 'UTF-8');
            });
        }

        $analysis['total_locations'] = count($filteredLocations);

        // Analyze by city
        $cityStats = [];
        foreach ($filteredLocations as $loc) {
            $locationCity = $this->extractCityFromAddress($loc['address'] ?? '');
            if (!isset($cityStats[$locationCity])) {
                $cityStats[$locationCity] = ['count' => 0, 'total_spaces' => 0, 'avg_price' => 0, 'price_sum' => 0, 'price_count' => 0];
            }
            $cityStats[$locationCity]['count']++;
            $cityStats[$locationCity]['total_spaces'] += count($loc['spaces'] ?? []);
            
            foreach ($loc['spaces'] ?? [] as $space) {
                if (isset($space['price'])) {
                    $cityStats[$locationCity]['price_sum'] += $space['price'];
                    $cityStats[$locationCity]['price_count']++;
                }
            }
        }

        foreach ($cityStats as $cityName => $stats) {
            $avgPrice = $stats['price_count'] > 0 ? $stats['price_sum'] / $stats['price_count'] : 0;
            $analysis['city_breakdown'][] = [
                'city' => $cityName,
                'locations' => $stats['count'],
                'total_spaces' => $stats['total_spaces'],
                'avg_price' => round($avgPrice, 0)
            ];
        }

        // Price analysis
        $prices = [];
        foreach ($filteredLocations as $loc) {
            foreach ($loc['spaces'] ?? [] as $space) {
                if (isset($space['price'])) {
                    $prices[] = $space['price'];
                }
            }
        }

        if (!empty($prices)) {
            sort($prices);
            $analysis['price_analysis'] = [
                'min_price' => min($prices),
                'max_price' => max($prices),
                'avg_price' => round(array_sum($prices) / count($prices), 0),
                'median_price' => $prices[count($prices) / 2] ?? 0
            ];
        }

        // Business insights
        if ($businessType) {
            $analysis['business_insights'] = $this->generateBusinessInsights($businessType, $city, $analysis['price_analysis']);
        }

        // Area insights
        $analysis['area_insights'] = $this->generateAreaInsights($filteredLocations, $city);

        // Recommendations
        $analysis['recommendations'] = $this->generateAnalysisRecommendations($analysis, $budget, $businessType, $language);

        return $analysis;
    }

    /**
     * Generate business insights based on analysis
     */
    private function generateBusinessInsights(string $businessType, ?string $city, array $priceAnalysis): array
    {
        $insights = [];
        
        if ($businessType === 'toko roti' || $businessType === 'bakery') {
            $insights[] = "Toko roti membutuhkan lokasi dengan arus kendaraan dan pejalan kaki yang stabil";
            $insights[] = "Area dekat perkantoran atau residensial sangat ideal";
            $insights[] = "Ukuran 10-30 m² sudah cukup untuk operasional";
        } elseif ($businessType === 'cafe' || $businessType === 'restoran') {
            $insights[] = "Cafe/restoran membutuhkan lokasi strategis dengan akses mudah";
            $insights[] = "Area dekat perkantoran atau pusat perbelanjaan sangat potensial";
            $insights[] = "Ukuran 20-50 m² ideal untuk kapasitas 15-30 orang";
        } elseif ($businessType === 'toko kelontong' || $businessType === 'minimarket') {
            $insights[] = "Toko kelontong membutuhkan lokasi dekat area residensial";
            $insights[] = "Akses parkir dan lalu lintas yang lancar sangat penting";
            $insights[] = "Ukuran 15-40 m² sudah memadai untuk operasional";
        }

        if ($city) {
            $insights[] = "Di {$city}, ada banyak pilihan lokasi yang sesuai untuk {$businessType}";
        }

        if (!empty($priceAnalysis)) {
            $avgPrice = $priceAnalysis['avg_price'] ?? 0;
            $insights[] = "Harga rata-rata sewa di area ini: IDR " . number_format($avgPrice, 0, ',', '.') . " per m² per bulan";
        }

        return $insights;
    }

    /**
     * Generate area insights
     */
    private function generateAreaInsights(array $locations, ?string $city): array
    {
        $insights = [];
        
        if ($city) {
            $insights[] = "Area {$city} memiliki " . count($locations) . " lokasi tersedia";
        }

        // Analyze by address patterns
        $addressPatterns = [];
        foreach ($locations as $loc) {
            $address = $loc['address'] ?? '';
            if (str_contains(mb_strtolower($address, 'UTF-8'), 'spbu')) {
                $addressPatterns['spbu'] = ($addressPatterns['spbu'] ?? 0) + 1;
            }
            if (str_contains(mb_strtolower($address, 'UTF-8'), 'pusat')) {
                $addressPatterns['pusat'] = ($addressPatterns['pusat'] ?? 0) + 1;
            }
            if (str_contains(mb_strtolower($address, 'UTF-8'), 'perkantoran')) {
                $addressPatterns['perkantoran'] = ($addressPatterns['perkantoran'] ?? 0) + 1;
            }
        }

        foreach ($addressPatterns as $pattern => $count) {
            $insights[] = "Ada {$count} lokasi di area {$pattern}";
        }

        return $insights;
    }

    /**
     * Generate analysis recommendations
     */
    private function generateAnalysisRecommendations(array $analysis, ?float $budget, string $businessType, string $language = 'id'): array
    {
        $recommendations = [];

        if ($language === 'en') {
            if ($analysis['total_locations'] > 0) {
                $recommendations[] = "Found {$analysis['total_locations']} locations available in your target area";
            }

            if (!empty($analysis['price_analysis'])) {
                $priceInfo = $analysis['price_analysis'];
                $recommendations[] = "Price range: IDR " . number_format($priceInfo['min_price'], 0, ',', '.') . " - IDR " . number_format($priceInfo['max_price'], 0, ',', '.') . " per m² per month";
            }

            if ($budget && !empty($analysis['price_analysis'])) {
                $avgPrice = $analysis['price_analysis']['avg_price'] ?? 0;
                if ($budget >= $avgPrice) {
                    $recommendations[] = "Your budget is suitable for most locations in this area";
                } else {
                    $recommendations[] = "Consider adjusting your budget or looking for smaller spaces";
                }
            }

            if ($businessType) {
                $recommendations[] = "For {$businessType}, focus on locations with good foot traffic and accessibility";
            }
        } else {
            if ($analysis['total_locations'] > 0) {
                $recommendations[] = "Ditemukan {$analysis['total_locations']} lokasi tersedia di area target Anda";
            }

            if (!empty($analysis['price_analysis'])) {
                $priceInfo = $analysis['price_analysis'];
                $recommendations[] = "Kisaran harga: IDR " . number_format($priceInfo['min_price'], 0, ',', '.') . " - IDR " . number_format($priceInfo['max_price'], 0, ',', '.') . " per m² per bulan";
            }

            if ($budget && !empty($analysis['price_analysis'])) {
                $avgPrice = $analysis['price_analysis']['avg_price'] ?? 0;
                if ($budget >= $avgPrice) {
                    $recommendations[] = "Budget Anda cocok untuk sebagian besar lokasi di area ini";
                } else {
                    $recommendations[] = "Pertimbangkan menyesuaikan budget atau mencari ruang yang lebih kecil";
                }
            }

            if ($businessType) {
                $recommendations[] = "Untuk {$businessType}, fokus pada lokasi dengan arus kendaraan dan aksesibilitas yang baik";
            }
        }

        return $recommendations;
    }
}


