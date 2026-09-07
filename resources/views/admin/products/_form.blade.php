@php
    $isEdit = isset($product);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Main Info --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Name --}}
        <div class="bg-white rounded-xl shadow-sm border border-[#efe8e3] p-6">
            <h3 class="font-serif text-lg font-semibold text-[#3d3d3d] mb-4">Thông tin cơ bản</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-[#3d3d3d] mb-1.5">Tên sản phẩm *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
                           placeholder="Nhập tên sản phẩm..."
                           class="w-full border border-[#efe8e3] rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent transition-all">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#3d3d3d] mb-1.5">Mô tả sản phẩm</label>
                    <textarea name="description" rows="5"
                              placeholder="Mô tả chi tiết sản phẩm..."
                              class="w-full border border-[#efe8e3] rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent transition-all">{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Pricing --}}
        <div class="bg-white rounded-xl shadow-sm border border-[#efe8e3] p-6">
            <h3 class="font-serif text-lg font-semibold text-[#3d3d3d] mb-4">Giá bán</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#3d3d3d] mb-1.5">Giá gốc (₫) *</label>
                    <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" required min="0"
                           placeholder="0"
                           class="w-full border border-[#efe8e3] rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent transition-all">
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#3d3d3d] mb-1.5">Giá khuyến mãi (₫)</label>
                    <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price ?? '') }}" min="0"
                           placeholder="Để trống nếu không giảm"
                           class="w-full border border-[#efe8e3] rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent transition-all">
                    @error('sale_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-5">

        {{-- Category + Stock --}}
        <div class="bg-white rounded-xl shadow-sm border border-[#efe8e3] p-6">
            <h3 class="font-serif text-lg font-semibold text-[#3d3d3d] mb-4">Phân loại & Kho hàng</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-[#3d3d3d] mb-1.5">Danh mục *</label>
                    <select name="category_id" required
                            class="w-full border border-[#efe8e3] rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent transition-all bg-white">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#3d3d3d] mb-1.5">Số lượng tồn kho *</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required min="0"
                           class="w-full border border-[#efe8e3] rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent transition-all">
                    @error('stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#3d3d3d] mb-1.5">Thương hiệu</label>
                    <input type="text" name="brand" value="{{ old('brand', $product->brand ?? '') }}"
                           placeholder="VD: Apple, Samsung..."
                           class="w-full border border-[#efe8e3] rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#c9a9a6] focus:border-transparent transition-all">
                    @error('brand')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-white rounded-xl shadow-sm border border-[#efe8e3] p-6">
            <h3 class="font-serif text-lg font-semibold text-[#3d3d3d] mb-4">Trạng thái</h3>

            <div class="space-y-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="radio" name="status" value="active"
                           {{ old('status', $product->status ?? 'active') === 'active' ? 'checked' : '' }}
                           class="w-4 h-4 border-gray-300 text-[#b8847e] focus:ring-[#c9a9a6]">
                    <span class="text-sm text-[#3d3d3d]">Đang bán</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="radio" name="status" value="inactive"
                           {{ old('status', $product->status ?? '') === 'inactive' ? 'checked' : '' }}
                           class="w-4 h-4 border-gray-300 text-[#b8847e] focus:ring-[#c9a9a6]">
                    <span class="text-sm text-[#3d3d3d]">Ngừng bán</span>
                </label>
            </div>
        </div>
    </div>
</div>

{{-- Actions --}}
<div class="flex items-center justify-end gap-3 mt-6">
    <a href="{{ route('admin.products.index') }}"
       class="px-5 py-2.5 border border-[#c9a9a6] text-[#b8847e] rounded-lg text-sm font-medium hover:bg-[#e8c4c4] transition-colors">
        Hủy
    </a>
    <button type="submit"
            class="px-5 py-2.5 bg-[#b8847e] text-white rounded-lg text-sm font-medium hover:bg-[#a6736d] transition-colors btn-shine">
        {{ $isEdit ? 'Cập nhật' : 'Thêm sản phẩm' }}
    </button>
</div>
