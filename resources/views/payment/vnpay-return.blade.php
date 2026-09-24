<x-layouts.app title="Kết quả thanh toán">
    <div class="max-w-3xl mx-auto px-4 py-12 text-center">
        @if ($status === 'success')
            <div class="bg-green-100 text-green-700 p-6 rounded-xl border border-green-200">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h1 class="text-3xl font-bold mb-2">Thanh toán thành công!</h1>
                <p class="text-lg">{{ $message }}</p>
                <p class="mt-4 font-mono bg-white inline-block px-3 py-1 rounded">Mã giao dịch: {{ $transactionCode }}</p>
            </div>
        @else
            <div class="bg-red-100 text-red-700 p-6 rounded-xl border border-red-200">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h1 class="text-3xl font-bold mb-2">Thanh toán thất bại</h1>
                <p class="text-lg">{{ $message }}</p>
            </div>
        @endif

        <div class="mt-8">
            <a href="{{ route('orders.index') }}" class="inline-block bg-[#b8847e] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#a6736d] transition">
                Xem đơn hàng của tôi
            </a>
            <a href="{{ route('home') }}" class="inline-block bg-gray-200 text-gray-800 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition ml-4">
                Về trang chủ
            </a>
        </div>
    </div>
</x-layouts.app>
