<x-layouts.app title="Trang chủ — SocialShop">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-serif font-bold text-[#3d3d3d]">Sản phẩm nổi bật</h1>
            <a href="{{ route('products.index') }}" class="text-[#b8847e] hover:text-[#a6736d] font-medium text-sm transition-colors">
                Xem tất cả &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-xl shadow-sm border border-[#efe8e3] overflow-hidden group hover:shadow-md transition-all duration-300 relative">
                    
                    {{-- Badge Sale --}}
                    @if($product->isOnSale())
                        <div class="absolute top-3 right-3 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded">
                            SALE
                        </div>
                    @endif

                    {{-- Image Placeholder --}}
                    <a href="{{ route('products.show', $product->slug) }}" class="block h-48 bg-[#f5f0ec] overflow-hidden">
                        @if($product->images->isNotEmpty())
                            <img src="{{ $product->images->first()->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[#c9a9a6]">
                                <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </a>

                    {{-- Info --}}
                    <div class="p-4">
                        <h3 class="font-medium text-[#3d3d3d] mb-1 line-clamp-2 min-h-[2.5rem]">
                            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-[#b8847e] transition-colors">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="font-bold text-[#b8847e]">{{ number_format($product->effectivePrice()) }} ₫</span>
                            @if($product->isOnSale())
                                <span class="text-xs text-[#9a9490] line-through">{{ number_format($product->price) }} ₫</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app>
