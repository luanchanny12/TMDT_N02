<x-layouts.app :title="'Sản phẩm yêu thích'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="font-serif text-3xl font-bold text-[#3d3d3d] mb-8">Sản phẩm yêu thích</h1>

        @if($products->count())
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <x-product-card :product="$product" :wishlistIds="$wishlistIds" />
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-20 animate-fade-in-up">
                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-[#f5f0ec] to-[#efe8e3] rounded-full flex items-center justify-center">
                    <svg class="h-12 w-12 text-[#c9a9a6]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h2 class="font-serif text-xl font-semibold text-[#3d3d3d] mb-2">Chưa có sản phẩm yêu thích</h2>
                <p class="text-[#9a9490] mb-6 max-w-sm mx-auto text-sm">
                    Hãy nhấn vào biểu tượng trái tim trên sản phẩm để lưu lại những món đồ bạn yêu thích.
                </p>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 bg-[#b8847e] text-white px-6 py-2.5 rounded-lg font-medium text-sm hover:bg-[#a6736d] transition-colors">
                    Khám phá sản phẩm
                </a>
            </div>
        @endif
    </div>
</x-layouts.app>
