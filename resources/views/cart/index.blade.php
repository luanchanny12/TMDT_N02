<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giỏ hàng — DK Social Commerce</title>
</head>
<body>
<h1>Giỏ hàng</h1>

@if($cart->items->isEmpty())
    <p>Giỏ hàng trống. <a href="{{ route('products.index') }}">Tiếp tục mua sắm →</a></p>
@else
    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart->items as $item)
            <tr id="cart-item-{{ $item->id }}">
                <td>{{ $item->product->name }}</td>
                <td>{{ number_format($item->product->effectivePrice()) }} ₫</td>
                <td>
                    <input type="number" value="{{ $item->quantity }}" min="1"
                        max="{{ $item->product->stock }}"
                        onchange="updateItem({{ $item->id }}, this.value)">
                </td>
                <td id="subtotal-{{ $item->id }}">{{ number_format($item->subtotal()) }} ₫</td>
                <td>
                    <button onclick="removeItem({{ $item->id }})">Xóa</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Tổng cộng: <span id="cart-total">{{ number_format($cart->totalPrice()) }}</span> ₫</strong></p>
    {{-- Nút checkout sẽ được thêm vào Day 7 --}}
    <a href="{{ route('products.index') }}">← Tiếp tục mua sắm</a>

    <script>
    const csrf = document.querySelector('[name=csrf-token]')?.content
              || document.querySelector('meta[name=csrf-token]').content;

    async function updateItem(id, qty) {
        const res  = await fetch(`/cart/${id}`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ quantity: parseInt(qty) }),
        });
        const json = await res.json();
        if (!res.ok) { alert(json.message); return; }
        document.getElementById(`subtotal-${id}`).textContent = json.subtotal.toLocaleString('vi-VN') + ' ₫';
        document.getElementById('cart-total').textContent       = json.cart_total.toLocaleString('vi-VN');
    }

    async function removeItem(id) {
        if (!confirm('Xóa sản phẩm này khỏi giỏ hàng?')) return;
        const res = await fetch(`/cart/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        });
        const json = await res.json();
        if (res.ok) {
            document.getElementById(`cart-item-${id}`).remove();
        } else {
            alert(json.message);
        }
    }
    </script>
@endif
</body>
</html>
