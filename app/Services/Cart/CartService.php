<?php

namespace App\Services\Cart;

use App\Exceptions\CartException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\BaseService;

class CartService extends BaseService
{
    /**
     * Lấy giỏ hàng của user (tạo mới nếu chưa có).
     */
    public function getOrCreateCart(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    /**
     * Lấy giỏ hàng đã load đủ relationships để hiển thị.
     */
    public function getCartWithItems(int $userId): Cart
    {
        $cart = $this->getOrCreateCart($userId);

        return $cart->load([
            'items.product.images' => fn ($q) => $q->where('is_primary', true)->limit(1),
        ]);
    }

    /**
     * Thêm sản phẩm vào giỏ.
     * - Nếu sản phẩm đã có trong giỏ → cộng thêm số lượng.
     * - Kiểm tra tồn kho trước khi thêm.
     *
     * @throws CartException Khi sản phẩm không active hoặc tồn kho không đủ.
     */
    public function addItem(int $userId, int $productId, int $quantity = 1): CartItem
    {
        $product = Product::active()->findOrFail($productId);

        $cart = $this->getOrCreateCart($userId);

        // Kiểm tra item đã có trong giỏ chưa
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        $currentQty  = $cartItem ? $cartItem->quantity : 0;
        $newTotalQty = $currentQty + $quantity;

        // Kiểm tra tồn kho
        if ($product->stock < $newTotalQty) {
            throw new CartException(
                "Sản phẩm \"{$product->name}\" chỉ còn {$product->stock} trong kho."
            );
        }

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $newTotalQty,
                'price'    => $product->effectivePrice(),
            ]);
        } else {
            $cartItem = CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $productId,
                'quantity'   => $quantity,
                'price'      => $product->effectivePrice(),
            ]);
        }

        return $cartItem->load('product');
    }

    /**
     * Cập nhật số lượng của 1 CartItem.
     *
     * @throws CartException Khi số lượng mới vượt tồn kho.
     */
    public function updateItem(int $userId, int $cartItemId, int $quantity): CartItem
    {
        $cart     = $this->getOrCreateCart($userId);
        $cartItem = CartItem::where('id', $cartItemId)
            ->where('cart_id', $cart->id)
            ->with('product')
            ->firstOrFail();

        if ($cartItem->product->stock < $quantity) {
            throw new CartException(
                "Sản phẩm \"{$cartItem->product->name}\" chỉ còn {$cartItem->product->stock} trong kho."
            );
        }

        $cartItem->update([
            'quantity' => $quantity,
            'price'    => $cartItem->product->effectivePrice(),
        ]);

        return $cartItem;
    }

    /**
     * Xóa 1 item khỏi giỏ hàng.
     * Throw ModelNotFoundException nếu item không thuộc cart của user này.
     */
    public function removeItem(int $userId, int $cartItemId): void
    {
        $cart = $this->getOrCreateCart($userId);

        // firstOrFail() đảm bảo chỉ xóa được item thuộc cart của chính user → tránh IDOR
        CartItem::where('id', $cartItemId)
            ->where('cart_id', $cart->id)
            ->firstOrFail()
            ->delete();
    }

    /**
     * Xóa toàn bộ giỏ hàng (dùng sau khi đặt hàng thành công).
     */
    public function clearCart(int $userId): void
    {
        $cart = Cart::where('user_id', $userId)->first();
        $cart?->items()->delete();
    }

    /**
     * Đếm tổng số lượng sản phẩm trong giỏ (dùng hiển thị badge trên navbar).
     */
    public function getItemCount(int $userId): int
    {
        $cart = Cart::where('user_id', $userId)->first();
        if (!$cart) {
            return 0;
        }

        return (int) CartItem::where('cart_id', $cart->id)->sum('quantity');
    }
}
