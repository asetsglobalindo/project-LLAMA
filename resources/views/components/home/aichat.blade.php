<div class="flex flex-col bg-white p-4 lg:p-5 shadow-md gap-2 lg:gap-5 w-full border-1 rounded-2xl">

<div class="mb-6" x-data="aiChatComponent()">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-custom-primary rounded-full flex items-center justify-center text-white text-sm">AI</div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">AVIS</p>
                        <p class="text-xs text-green-600">● Online</p>
                    </div>
                </div>
                <button @click="toggle()" class="text-xs text-custom-primary hover:underline">
                    <span x-text="open ? 'Tutup' : 'Buka'"></span>
                </button>
            </div>
            <div x-show="open" x-cloak>
                <div class="p-4 space-y-3 max-h-80 overflow-y-auto" x-ref="container">
                    <template x-for="(msg, idx) in messages" :key="idx">
                        <div class="flex" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
                            <div class="max-w-[80%] text-sm rounded-lg px-3 py-2"
                                 :class="msg.role === 'user' ? 'bg-custom-primary text-white' : 'bg-gray-100 text-gray-800'">
                                <template x-if="msg.isStructured">
                                    <div x-html="msg.content" class="ai-structured-content"></div>
                                </template>
                                <template x-if="!msg.isStructured">
                                    <p x-text="msg.content"></p>
                                </template>
                                <template x-if="(msg.recommendations || []).length">
                                    <div class="mt-3">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                            <template x-for="(rec, rIdx) in msg.recommendations" :key="rIdx">
                                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden flex flex-col h-full">
                                                    <!-- Image -->
                                                    <div class="w-full h-32 bg-gray-100 flex items-center justify-center">
                                                        <template x-if="rec.image">
                                                            <img :src="rec.image" :alt="rec.title || rec.name" class="w-full h-full object-cover">
                                                        </template>
                                                        <template x-if="!rec.image">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                            </svg>
                                                        </template>
                                                    </div>
                                                    
                                                    <!-- Content -->
                                                    <div class="p-3 flex flex-col h-full">
                                                        <div class="flex-1">
                                                            <h4 class="text-sm font-semibold text-gray-900 mb-1 overflow-hidden" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;" x-text="rec.title || rec.name"></h4>
                                                            <p class="text-xs text-gray-600 mb-2 overflow-hidden" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;" x-text="rec.description || ''"></p>
                                                        </div>
                                                        <div class="flex items-center justify-between mt-auto">
                                                            <span class="text-xs font-medium text-custom-primary" x-text="rec.price || ''"></span>
                                                            <button @click="navigateToRecommendation(rec)" class="text-xs bg-custom-primary text-white px-2 py-1 rounded hover:bg-custom-primary/90 transition-colors">
                                                                Lihat
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                    <div x-show="isTyping" class="flex items-center gap-2 text-xs text-gray-500">
                        <span>AI sedang mengetik</span>
                        <div class="flex gap-1"><span class="w-1.5 h-1.5 bg-custom-primary rounded-full animate-bounce"></span><span class="w-1.5 h-1.5 bg-custom-primary rounded-full animate-bounce" style="animation-delay:150ms"></span><span class="w-1.5 h-1.5 bg-custom-primary rounded-full animate-bounce" style="animation-delay:300ms"></span></div>
                    </div>
                </div>
                <div class="border-t border-gray-100 p-3 flex items-center gap-2">
                    <input type="text" x-model="input" @keydown.enter.prevent="send()" :disabled="loading"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-custom-primary/40"
                           placeholder="Tanya lokasi: kota, ukuran, dan budget per bulan...">
                    <button @click="send()" :disabled="loading || !input.trim()"
                            class="bg-custom-primary text-white text-sm px-3 py-2 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed">Kirim</button>
                </div>
            </div>
        </div>
    </div>
    
</div>

@push('scripts')
<script>
function aiChatComponent() {
    return {
        open: true,
        input: '',
        messages: [
            { role: 'assistant', content: 'Halo! 👋 Saya AVIS AI, credible bestie bisnis kamu. Siap bantuin kamu analisis, strategi, dan eksekusi ide bisnismu bareng-bareng 💼✨.\n\nYuk, mulai dari mana dulu nih?' }
        ],
        loading: false,
        isTyping: false,
        toggle() { this.open = !this.open; },
        scrollToBottom() { this.$nextTick(() => { const el = this.$refs.container; if (el) el.scrollTop = el.scrollHeight; }); },
        async send() {
            const text = (this.input || '').trim();
            if (!text || this.loading) return;
            this.messages.push({ role: 'user', content: text });
            this.input = '';
            this.loading = true;
            this.scrollToBottom();
            try {
                const url = (window?.location?.origin || '') + '/api/ai/chat';
                const payload = { message: text, context: this.messages.slice(-3) };
                console.log('[AI] POST', url, payload);
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(payload)
                });
                console.log('[AI] status', response.status);
                if (!response.ok) {
                    const errText = await response.text();
                    console.error('[AI] non-OK response', response.status, errText);
                    throw new Error('AI endpoint returned ' + response.status);
                }
                const data = await response.json();
                console.log('[AI] data', data);
                const content = data?.data?.message || 'Maaf, belum ada jawaban.';
                const structuredContent = data?.data?.structured_message || content;
                const recs = data?.data?.recommendations || [];
                this.isTyping = true;
                let shown = '';
                const id = setInterval(() => {
                    if (shown.length < content.length) {
                        shown = content.slice(0, shown.length + 4);
                        if (this.messages[this.messages.length - 1]?.role === 'assistant_typing') {
                            this.messages[this.messages.length - 1].content = shown;
                        } else {
                            this.messages.push({ role: 'assistant_typing', content: shown });
                        }
                        this.scrollToBottom();
                    } else {
                        clearInterval(id);
                        if (this.messages[this.messages.length - 1]?.role === 'assistant_typing') {
                            this.messages.pop();
                        }
                        this.messages.push({ role: 'assistant', content: structuredContent, recommendations: recs, isStructured: true });
                        this.isTyping = false;
                        this.scrollToBottom();
                    }
                }, 12);
            } catch (e) {
                console.error('[AI] fetch error', e);
                this.messages.push({ role: 'assistant', content: 'Maaf, sedang ada gangguan. Coba lagi sebentar ya.' });
            } finally {
                this.loading = false;
            }
        },
        
        navigateToRecommendation(rec) {
            if (rec.source === 'pms') {
                window.location.href = `/detail-spbu/${rec.id}`;
            } else {
                window.location.href = `/detail-spbu/${rec.id}`;
            }
        }
    }
}
</script>
@endpush

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    
    /* AI Structured Response Styles */
    .ai-structured-content {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    .ai-structured-response {
        max-width: 100%;
    }
    
    .ai-section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        margin: 0 0 0.75rem 0;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .ai-subtitle {
        font-size: 0.875rem;
        font-weight: 600;
        color: #4b5563;
        margin: 0 0 0.5rem 0;
    }
    
    .ai-description {
        font-size: 0.875rem;
        color: #6b7280;
        margin: 0 0 0.75rem 0;
        line-height: 1.5;
    }
    
    .ai-analysis-item {
        background: #f9fafb;
        padding: 0.75rem;
        border-radius: 0.5rem;
        margin-bottom: 0.75rem;
        border-left: 3px solid #3b82f6;
    }
    
    .ai-price-range {
        background: #f0f9ff;
        padding: 0.75rem;
        border-radius: 0.5rem;
        margin-bottom: 0.75rem;
        border-left: 3px solid #0ea5e9;
    }
    
    .ai-recommendations-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .ai-recommendation-item {
        background: #fef3c7;
        padding: 0.75rem;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
        border-left: 3px solid #f59e0b;
    }
    
    .ai-location {
        font-size: 0.75rem;
        color: #6b7280;
        font-style: italic;
    }
    
    .ai-price {
        font-size: 0.75rem;
        color: #059669;
        font-weight: 600;
    }
    
    .ai-tips-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .ai-tips-list li {
        background: #f0fdf4;
        padding: 0.5rem 0.75rem;
        border-radius: 0.375rem;
        margin-bottom: 0.5rem;
        border-left: 3px solid #10b981;
        font-size: 0.875rem;
        color: #374151;
    }
    
    .ai-tips-list li:before {
        content: "✓ ";
        color: #10b981;
        font-weight: bold;
    }
    
    .ai-main-message {
        margin-bottom: 1rem;
    }
    
    .ai-message-content {
        background: #f3f4f6;
        padding: 0.75rem;
        border-radius: 0.5rem;
        border-left: 3px solid #6b7280;
    }
    
    .ai-analysis-section,
    .ai-recommendations-summary,
    .ai-tips-section {
        margin-bottom: 1rem;
    }
    
    .ai-analysis-content,
    .ai-summary-content,
    .ai-tips-content {
        padding-left: 0.5rem;
    }
</style>
@endpush

