<x-layouts.app :title="'Đặt hàng thành công'">

    <div class="max-w-lg mx-auto px-4 py-12 md:py-20">
        <div class="bg-white rounded-xl shadow-sm border border-[#efe8e3] p-8 md:p-12 text-center animate-fade-in-up">

            {{-- Success Icon --}}
            <div class="w-20 h-20 mx-auto mb-6 bg-green-50 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            {{-- Heading --}}
            <h1 class="font-serif text-2xl md:text-3xl font-bold text-[#3d3d3d] mb-3">
                Cảm ơn bạn đã đặt hàng!
            </h1>
            <p class="text-[#9a9490] text-sm mb-8">
                Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.
            </p>

            {{-- Order Code --}}
            <div class="bg-[#faf7f4] rounded-xl p-5 mb-8">
                <p class="text-xs text-[#9a9490] uppercase tracking-wide mb-1">Mã đơn hàng</p>
                <p class="text-2xl font-extrabold text-[#b8847e] tracking-wide">
                    #{{ $order->order_code }}
                </p>
            </div>

            {{-- Order Summary --}}
            <div class="text-left space-y-4 mb-8">
                <div class="flex justify-between text-sm">
                    <span class="text-[#9a9490]">Tổng cộng</span>
                    <span class="font-bold text-[#b8847e]">{{ number_format($order->total, 0, ',', '.') }}₫</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-[#9a9490]">Phương thức thanh toán</span>
                    <span class="font-medium text-[#3d3d3d]">{{ strtoupper($order->payment_method) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-[#9a9490]">Địa chỉ giao hàng</span>
                    <span class="font-medium text-[#3d3d3d] text-right max-w-[60%]">{{ $order->shipping_address }}</span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('orders.show', $order) }}"
                   class="flex-1 bg-[#b8847e] text-white px-6 py-3 rounded-lg font-medium text-sm hover:bg-[#a6736d] transition-colors text-center">
                    Xem chi tiết đơn hàng
                </a>
                <a href="{{ route('products.index') }}"
                   class="flex-1 border border-[#c9a9a6] text-[#b8847e] px-6 py-3 rounded-lg font-medium text-sm hover:bg-[#e8c4c4] transition-colors text-center">
                    Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>

</x-layouts.app>
