<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cod,vnpay',
        ]);

        $cartItems = Cart::getContent();
        $cartTotal = Cart::getTotal();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $order = Order::create([
            'user_id'          => Auth::id(),
            'order_code'       => $orderCode,
            'subtotal'         => $cartTotal,
            'discount'         => 0,
            'shipping_fee'     => 0,
            'total'            => $cartTotal,
            'shipping_name'    => $validated['shipping_name'],
            'shipping_phone'   => $validated['shipping_phone'],
            'shipping_address' => $validated['shipping_address'],
            'note'             => $validated['shipping_notes'] ?? null,
            'payment_method'   => $validated['payment_method'],
            'status'           => 'pending',
        ]);

        foreach ($cartItems as $item) {
            $itemSubtotal = $item->price * $item->quantity;
            $order->items()->create([
                'product_id'   => $item->id,
                'product_name' => $item->name,
                'quantity'     => $item->quantity,
                'price'        => $item->price,
                'subtotal'     => $itemSubtotal,
            ]);
        }

        Cart::clear();

        return redirect()->route('orders.success', $order);
    }

    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.success', compact('order'));
    }
}
