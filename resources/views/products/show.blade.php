<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->name }} — DK Social Commerce</title>
</head>
<body>
<h1>{{ $product->name }}</h1>
<p>Danh mục: {{ $product->category?->name }}</p>
<p>Giá: {{ number_format($product->effectivePrice()) }} ₫
    @if($product->isOnSale())
        <s>{{ number_format($product->price) }} ₫</s>
    @endif
</p>
<p>Tồn kho: {{ $product->stock }}</p>
<p>{{ $product->description }}</p>

@auth
<form id="add-to-cart-form">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}">
    <button type="submit">Thêm vào giỏ hàng</button>
</form>
<script>
    document.getElementById('add-to-cart-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const data = new FormData(this);
        const res  = await fetch('/cart', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('[name=_token]').value, 'Accept': 'application/json' },
            body: data,
        });
        const json = await res.json();
        alert(json.message);
    });
</script>
@else
    <a href="{{ route('login') }}">Đăng nhập để mua hàng</a>
@endauth

<hr>
<h3>Đánh giá ({{ $product->reviews->count() }})</h3>
@foreach($product->reviews as $review)
    <p>⭐ {{ $review->rating }}/5 — {{ $review->user->name }}: {{ $review->comment }}</p>
@endforeach

<a href="{{ route('products.index') }}">← Quay lại danh sách</a>
</body>
</html>
