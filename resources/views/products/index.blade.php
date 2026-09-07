<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sản phẩm — DK Social Commerce</title>
</head>
<body>
<h1>Danh sách sản phẩm</h1>

{{-- Bộ lọc --}}
<form method="GET" action="{{ route('products.index') }}">
    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Tìm kiếm...">
    <select name="category_id">
        <option value="">Tất cả danh mục</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" @selected(($filters['category_id'] ?? '') == $cat->id)>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
    <select name="sort">
        <option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>Mới nhất</option>
        <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Giá tăng dần</option>
        <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Giá giảm dần</option>
    </select>
    <button type="submit">Lọc</button>
</form>

{{-- Danh sách --}}
<p>Tổng {{ $products->total() }} sản phẩm.</p>
<ul>
    @foreach($products as $product)
        <li>
            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
            — {{ number_format($product->effectivePrice()) }} ₫
            — Còn {{ $product->stock }} cái
        </li>
    @endforeach
</ul>

{{ $products->links() }}
</body>
</html>
