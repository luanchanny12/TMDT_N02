<div class="fixed bottom-6 right-6 z-50">
    {{-- Toggle Button --}}
    <button wire:click="toggle"
            class="w-14 h-14 rounded-full bg-gradient-to-br from-[#b8847e] to-[#c9a9a6] text-white flex items-center justify-center shadow-lg hover:shadow-xl transition-shadow relative">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <span class="absolute -top-1 -right-1 bg-[#faf7f4] text-[#b8847e] text-[9px] font-bold px-1.5 py-0.5 rounded-md border border-[#efe8e3]">AI</span>
    </button>

    {{-- Chat Window: dùng @if($isOpen) thay vì @entangle để tránh conflict Alpine --}}
    @if($isOpen)
    <div class="fixed bottom-24 right-6 w-[320px] h-[440px] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-[#efe8e3]">

        {{-- Header --}}
        <div class="bg-gradient-to-br from-[#b8847e] to-[#c9a9a6] text-white px-4 py-3 flex justify-between items-center flex-shrink-0">
            <div>
                <h3 class="font-serif font-semibold text-sm">Trợ lý SocialShop</h3>
                <p class="text-white/70 text-[11px]">Hỗ trợ 24/7</p>
            </div>
            <button wire:click="toggle" class="text-white/80 hover:text-white transition-colors p-1">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Messages --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-[#faf7f4]" id="chatMessages">
            @if(empty($messages))
                <div class="text-center py-4">
                    <div class="w-12 h-12 bg-[#f5f0ec] rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="h-6 w-6 text-[#b8847e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <p class="text-[#3d3d3d] font-medium text-sm mb-1">Xin chào! 👋</p>
                    <p class="text-[#9a9490] text-xs leading-relaxed">Tôi là trợ lý của SocialShop.<br>Bạn cần giúp gì?</p>

                    <div class="flex flex-wrap gap-2 justify-center mt-4">
                        <button wire:click="sendQuick('Giá cả các sản phẩm như thế nào?')"
                                class="bg-white text-[#b8847e] text-xs font-medium px-3 py-1.5 rounded-full border border-[#efe8e3] hover:bg-[#e8c4c4] hover:text-[#3d3d3d] hover:border-[#e8c4c4] transition-colors cursor-pointer">
                            💰 Giá cả
                        </button>
                        <button wire:click="sendQuick('Chính sách giao hàng như thế nào?')"
                                class="bg-white text-[#b8847e] text-xs font-medium px-3 py-1.5 rounded-full border border-[#efe8e3] hover:bg-[#e8c4c4] hover:text-[#3d3d3d] hover:border-[#e8c4c4] transition-colors cursor-pointer">
                            🚚 Giao hàng
                        </button>
                        <button wire:click="sendQuick('Làm thế nào để liên hệ hỗ trợ?')"
                                class="bg-white text-[#b8847e] text-xs font-medium px-3 py-1.5 rounded-full border border-[#efe8e3] hover:bg-[#e8c4c4] hover:text-[#3d3d3d] hover:border-[#e8c4c4] transition-colors cursor-pointer">
                            📞 Liên hệ
                        </button>
                    </div>
                </div>
            @endif

            @foreach($messages as $msg)
                <div class="{{ $msg['type'] === 'user' ? 'flex justify-end' : 'flex justify-start' }}">
                    @if($msg['type'] !== 'user')
                        <div class="w-6 h-6 bg-[#b8847e] rounded-full flex items-center justify-center flex-shrink-0 mr-2 mt-1">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                    @endif
                    <div class="{{ $msg['type'] === 'user'
                                    ? 'bg-[#b8847e] text-white rounded-xl rounded-br-sm'
                                    : 'bg-white text-[#3d3d3d] border border-[#efe8e3] rounded-xl rounded-bl-sm' }}
                                px-3 py-2 max-w-[75%] text-sm leading-relaxed whitespace-pre-wrap">
                        {{ $msg['content'] }}
                    </div>
                </div>
            @endforeach

            {{-- 3 dấu chấm hiện khi Livewire đang xử lý (wire:loading tự động) --}}
            <div wire:loading wire:target="sendMessage,sendQuick" class="flex justify-start">
                <div class="w-6 h-6 bg-[#b8847e] rounded-full flex items-center justify-center flex-shrink-0 mr-2 mt-1">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <div class="bg-white border border-[#efe8e3] rounded-xl rounded-bl-sm px-3 py-2">
                    <div class="flex gap-1 items-center h-4">
                        <span class="w-1.5 h-1.5 bg-[#b8847e] rounded-full animate-bounce" style="animation-delay:0s"></span>
                        <span class="w-1.5 h-1.5 bg-[#b8847e] rounded-full animate-bounce" style="animation-delay:.15s"></span>
                        <span class="w-1.5 h-1.5 bg-[#b8847e] rounded-full animate-bounce" style="animation-delay:.3s"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input --}}
        <form wire:submit.prevent="sendMessage" class="border-t border-[#efe8e3] p-3 flex-shrink-0 bg-white">
            <div class="flex gap-2 items-center">
                <input type="text" wire:model="message"
                       placeholder="Nhập tin nhắn..."
                       class="flex-1 border border-[#efe8e3] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent bg-[#faf7f4] focus:bg-white transition-all">
                <button type="submit"
                        class="bg-[#b8847e] text-white w-9 h-9 rounded-lg flex items-center justify-center hover:bg-[#a6736d] transition-colors flex-shrink-0 disabled:opacity-50"
                        wire:loading.attr="disabled">
                    <svg wire:loading.remove class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <svg wire:loading class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
    @endif
</div>

@push('scripts')
<script>
    Livewire.on('scrollToBottom', () => {
        setTimeout(() => {
            const el = document.getElementById('chatMessages');
            if (el) el.scrollTop = el.scrollHeight;
        }, 100);
    });
</script>
@endpush
