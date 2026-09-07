<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DK Social Commerce</title>
</head>
<body>
<h1>Trang chủ — DK Social Commerce</h1>
<p>Có {{ $products->count() }} sản phẩm nổi bật. (Frontend đang phát triển)</p>
<ul>
    @foreach($products as $product)
        <li>
            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
            — {{ number_format($product->effectivePrice()) }} ₫
            @if($product->isOnSale()) (SALE) @endif
        </li>
    @endforeach
</ul>
<a href="{{ route('products.index') }}">Xem tất cả sản phẩm →</a>
</body>
</html>
