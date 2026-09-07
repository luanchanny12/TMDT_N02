<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::getContent();
        $cartTotal = Cart::getTotal();

        return view('cart.index', compact('cartItems', 'cartTotal'));
    }

    public function add(Request $request)
    {
        $product = \App\Models\Product::with('images')->findOrFail($request->product_id);

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'quantity' => $request->quantity ?? 1,
            'price' => $product->sale_price ?? $product->price,
            'attributes' => [
                'image' => $product->image_url,
                'slug' => $product->slug,
            ],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cartCount' => Cart::getTotalQuantity(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function update(Request $request)
    {
        Cart::update($request->rowId, ['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Cập nhật giỏ hàng thành công!');
    }

    public function remove(Request $request)
    {
        Cart::remove($request->rowId);

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    public function clear()
    {
        Cart::clear();

        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }

    public function checkout()
    {
        $cartItems = Cart::getContent();
        $cartTotal = Cart::getTotal();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        return view('checkout.index', compact('cartItems', 'cartTotal'));
    }
}
