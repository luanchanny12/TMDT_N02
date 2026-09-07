<x-layouts.app>
    {{-- Hero --}}
    <section class="relative" style="background: linear-gradient(135deg, rgba(250,247,244,.86) 0%, rgba(243,235,230,.84) 50%, rgba(232,223,216,.86) 100%), url('https://images.unsplash.com/photo-1483985988355-763728e1935b?w=1600&h=900&fit=crop&q=85') center/cover no-repeat;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 text-center relative z-10">
            <p class="text-[11px] uppercase tracking-[3px] text-[#b8847e] mb-3 font-medium">Bộ sưu tập 2026</p>
            <h1 class="font-serif text-4xl md:text-5xl lg:text-[3.5rem] font-semibold text-[#3d3d3d] leading-tight mb-4">
                Thời trang<br><em class="italic text-[#b8847e]">dịu nhẹ</em> mỗi ngày
            </h1>
            <p class="text-[#6b6560] text-base md:text-lg max-w-lg mx-auto mb-8">
                Khám phá phong cách thanh lịch — từ áo thun basic đến váy dự tiệc, giá tốt &amp; ưu đãi đến 40%
            </p>
            <div class="flex gap-3 justify-center flex-wrap">
                <a href="{{ route('products.index') }}"
                   class="bg-[#b8847e] text-white px-7 py-3 rounded-lg font-medium text-sm hover:bg-[#a6736d] transition-colors">
                    Tất cả sản phẩm
                </a>
                <a href="{{ route('products.index', ['featured' => 1]) }}"
                   class="border border-[#c9a9a6] text-[#b8847e] bg-transparent px-7 py-3 rounded-lg font-medium text-sm hover:bg-[#e8c4c4] hover:text-[#3d3d3d] hover:border-[#e8c4c4] transition-colors">
                    Sản phẩm nổi bật
                </a>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Categories --}}
        @if($categories->count())
            <div class="text-center mb-6">
                <h2 class="font-serif text-2xl font-semibold text-[#3d3d3d]">Danh mục nổi bật</h2>
                <p class="text-[#9a9490] text-sm mt-1">Chọn phong cách của bạn</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-12">
                @foreach($categories->take(8) as $category)
                    <a href="{{ route('products.index', ['category' => $category->id]) }}"
                       class="block text-center bg-white border border-[#efe8e3] rounded-xl py-3.5 px-4 text-[#3d3d3d] font-medium text-sm hover:bg-[#e8c4c4] hover:border-[#e8c4c4] hover:text-[#3d3d3d] transition-colors">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Featured Products --}}
        @if($featuredProducts->count())
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="font-serif text-2xl font-semibold text-[#3d3d3d]">Gợi ý hôm nay</h2>
                    <p class="text-[#9a9490] text-sm mt-0.5">Sản phẩm nổi bật &amp; đang giảm giá</p>
                </div>
                <a href="{{ route('products.index') }}" class="text-[#b8847e] text-sm font-semibold hover:text-[#a6736d] transition-colors">
                    Xem tất cả →
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        @endif
    </div>

    {{-- Referral Banner --}}
    @auth
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
            <div class="bg-[#f5f0ec] border border-[#efe8e3] rounded-2xl p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="font-serif text-2xl font-semibold text-[#3d3d3d] mb-2">Giới thiệu bạn bè, nhận thưởng</h3>
                    <p class="text-[#6b6560] text-sm max-w-lg">Chia sẻ mã giới thiệu của bạn và nhận ngay ưu đãi khi bạn bè mua hàng thành công!</p>
                </div>
                <a href="{{ route('profile') }}"
                   class="bg-[#b8847e] text-white px-6 py-3 rounded-lg font-medium text-sm hover:bg-[#a6736d] transition-colors whitespace-nowrap flex-shrink-0">
                    Lấy mã giới thiệu
                </a>
            </div>
        </div>
    @endauth

    {{-- New Products --}}
    @if($newProducts->count())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10">
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="font-serif text-2xl font-semibold text-[#3d3d3d]">Sản phẩm mới nhất</h2>
                    <p class="text-[#9a9490] text-sm mt-0.5">Cập nhật xu hướng thời trang</p>
                </div>
                <a href="{{ route('products.index') }}" class="text-[#b8847e] text-sm font-semibold hover:text-[#a6736d] transition-colors">
                    Xem tất cả →
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($newProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    @endif
</x-layouts.app>
