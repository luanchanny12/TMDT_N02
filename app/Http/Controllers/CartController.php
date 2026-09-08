<?php

namespace App\Http\Controllers;

use App\Exceptions\CartException;
use App\Services\Cart\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService
    ) {}

    public function index()
    {
        $cart = $this->cartService->getCartWithItems(auth()->id());
        $cartItems = $cart->items;
        $cartTotal = $cart->totalPrice();

        return view('cart.index', compact('cartItems', 'cartTotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        try {
            $this->cartService->addItem(
                auth()->id(), 
                $request->product_id, 
                $request->quantity ?? 1
            );

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'cartCount' => $this->cartService->getItemCount(auth()->id()),
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
        } catch (CartException $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'rowId' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $this->cartService->updateItem(
                auth()->id(), 
                $request->rowId, 
                $request->quantity
            );
            return redirect()->route('cart.index')->with('success', 'Cập nhật giỏ hàng thành công!');
        } catch (CartException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }

    public function remove(Request $request)
    {
        $request->validate([
            'rowId' => 'required'
        ]);

        $this->cartService->removeItem(auth()->id(), $request->rowId);

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    public function clear()
    {
        $this->cartService->clearCart(auth()->id());

        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }

    public function checkout()
    {
        $cart = $this->cartService->getCartWithItems(auth()->id());
        $cartItems = $cart->items;
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $cartTotal = $cart->totalPrice();

        return view('checkout.index', compact('cartItems', 'cartTotal'));
    }
}
