<!-- AI Chat Widget - Di dalam Hero Section -->
<div class="w-full max-w-6xl mx-auto" x-data="aiChatComponent()">
    <div class="ai-widget bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-2xl shadow-2xl overflow-hidden relative transition-all duration-300 backdrop-blur-sm">
        <!-- Header dengan efek menarik -->
        <div class="relative bg-gradient-to-r from-custom-primary to-[#8dc0ca] text-white overflow-hidden">
            <!-- Background pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/20 to-transparent"></div>
                <div class="absolute bottom-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
            </div>
            
            <div class="relative flex items-center justify-between px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg overflow-hidden">
                            <img src="/assets/img/llama.gif" alt="AVIS AI" class="w-full h-full object-cover rounded-full">
                        </div>
                        <!-- Online indicator -->
                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-400 rounded-full border-3 border-white animate-pulse"></div>
                        <!-- Pulse ring -->
                        <div class="absolute inset-0 bg-green-400 rounded-full animate-ping opacity-30"></div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white mb-1">AVIS AI Assistant</h3>
                        <p class="text-sm text-green-200 flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            Online & Ready to Help Your Business
                        </p>
                    </div>
                </div>
                
                <!-- CTA Button -->
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block text-right">
                        <p class="text-xs text-white/80 mb-1">Ask anything about</p>
                        <p class="text-sm font-medium text-white">Business & Investment</p>
                    </div>
                    <button @click="toggle()" 
                            class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-xl transition-all duration-200 font-medium flex items-center gap-2 backdrop-blur-sm">
                        <span x-text="open ? 'Minimize' : 'Start Chat'"></span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Chat Interface -->
        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0">
            <!-- Chat Messages -->
            <div class="p-6 space-y-4 max-h-80 overflow-y-auto bg-gradient-to-b from-gray-50 to-white" x-ref="container">
                    <template x-for="(msg, idx) in messages" :key="idx">
                        <div class="flex" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
                            <div class="max-w-[85%] text-sm rounded-2xl px-4 py-3 shadow-sm"
                                 :class="msg.role === 'user' ? 'bg-gradient-to-r from-custom-primary to-[#8dc0ca] text-white' : 'bg-white text-gray-800 border border-gray-200'">
                                <template x-if="msg.isStructured">
                                    <div x-html="msg.content" class="ai-structured-content"></div>
                                </template>
                                <template x-if="!msg.isStructured">
                                    <p x-text="msg.content"></p>
                                </template>
                                <template x-if="(msg.recommendations || []).length">
                                    <div class="mt-3">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                            <template x-for="(rec, rIdx) in msg.recommendations" :key="rIdx">
                                                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col h-full group hover:-translate-y-1">
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
                                                            <span class="text-xs font-semibold text-custom-primary" x-text="rec.price || ''"></span>
                                                            <button @click="navigateToRecommendation(rec)" class="text-xs bg-gradient-to-r from-custom-primary to-[#8dc0ca] text-white px-3 py-1.5 rounded-lg hover:shadow-md transition-all duration-200 font-medium">
                                                                View Details
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
                    <div x-show="isTyping" class="flex items-center gap-3 text-sm text-gray-600 bg-white border border-gray-200 rounded-2xl px-4 py-3 shadow-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-custom-primary rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-custom-primary rounded-full animate-bounce" style="animation-delay:150ms"></div>
                            <div class="w-2 h-2 bg-custom-primary rounded-full animate-bounce" style="animation-delay:300ms"></div>
                        </div>
                        <span class="font-medium">AVIS AI is thinking...</span>
                    </div>
            </div>
            
            <!-- Input Area -->
            <div class="border-t border-gray-200 bg-white p-4">
                <div class="flex items-center gap-3">
                    <div class="flex-1 relative">
                        <input type="text" x-model="input" @keydown.enter.prevent="send()" :disabled="loading"
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-custom-primary/30 focus:border-custom-primary transition-all duration-200 bg-gray-50 focus:bg-white"
                               placeholder="Tanya tentang bisnis, lokasi, atau strategi investasi...">
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                    </div>
                    <button @click="send()" :disabled="loading || !input.trim()"
                            class="bg-gradient-to-r from-custom-primary to-[#8dc0ca] text-white text-sm px-6 py-3 rounded-2xl disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-lg transition-all duration-200 font-medium flex items-center gap-2">
                        <span x-show="!loading">Send</span>
                        <span x-show="loading" class="flex items-center gap-1">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Sending...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function aiChatComponent() {
    return {
        open: false, // Default tertutup, user harus klik "Start Chat"
        showNotification: false,
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
    
    /* AI Widget Styles */
    .ai-widget {
        position: relative;
        z-index: 10;
    }
    
    /* Pulse animation for notification */
    @keyframes pulse-glow {
        0%, 100% { 
            box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
        }
        50% { 
            box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
        }
    }
    
    .ai-pulse-glow {
        animation: pulse-glow 2s infinite;
    }
    
    /* Smooth transitions */
    .ai-transition {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Widget hover effects */
    .ai-widget:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    /* Gradient text effect */
    .ai-gradient-text {
        background: linear-gradient(135deg, #3b82f6, #8dc0ca);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
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

