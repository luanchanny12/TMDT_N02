<?php

namespace App\Http\Controllers;

use App\Services\Payment\PaymentService;
use App\Services\Payment\VNPayService;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private VNPayService $vnpayService
    ) {}

    public function vnpayReturn(Request $request)
    {
        $inputData = $request->all();

        if ($this->vnpayService->verifyCallback($inputData)) {
            $responseCode = $inputData['vnp_ResponseCode'] ?? '';
            
            if ($responseCode === '00') {
                return view('payment.vnpay-return', [
                    'status' => 'success',
                    'message' => 'Giao dịch thành công',
                    'transactionCode' => $inputData['vnp_TxnRef'] ?? '',
                ]);
            } else {
                return view('payment.vnpay-return', [
                    'status' => 'error',
                    'message' => 'Giao dịch không thành công hoặc đã bị hủy',
                ]);
            }
        }

        return view('payment.vnpay-return', [
            'status' => 'error',
            'message' => 'Chữ ký không hợp lệ (Invalid signature)',
        ]);
    }

    public function vnpayIpn(Request $request)
    {
        $inputData = $request->all();
        $result = $this->paymentService->processVNPayIpn($inputData);

        return response()->json($result);
    }
}
