<?php

namespace App\Http\Controllers;

use App\Exceptions\OrderException;
use App\Models\Order;
use App\Services\Order\OrderService;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private PaymentService $paymentService
    ) {}

    public function index()
    {
        $orders = $this->orderService->getOrdersForUser(Auth::id());

        return view('orders.index', compact('orders'));
    }

    public function show(int $orderId)
    {
        $order = $this->orderService->getOrderForUser($orderId, Auth::id());

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

        try {
            $order = $this->orderService->placeOrder(Auth::user(), [
                'shipping_name'    => $validated['shipping_name'],
                'shipping_phone'   => $validated['shipping_phone'],
                'shipping_address' => $validated['shipping_address'],
                'note'             => $validated['shipping_notes'] ?? null,
                'payment_method'   => $validated['payment_method'],
            ]);

            $payment = $this->paymentService->createPayment($order, $request->ip());

            if (isset($payment->redirect_url) && $payment->redirect_url) {
                return redirect()->away($payment->redirect_url);
            }

            return redirect()->route('orders.success', $order)->with('success', 'Đặt hàng thành công!');
        } catch (OrderException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function success(int $orderId)
    {
        $order = $this->orderService->getOrderForUser($orderId, Auth::id());

        return view('orders.success', compact('order'));
    }
}
