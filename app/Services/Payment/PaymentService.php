<?php

namespace App\Services\Payment;

use App\Services\BaseService;

/**
 * PaymentService — Day 8
 *
 * Business logic:
 *   - Xử lý thanh toán COD
 *   - Khởi tạo VNPay payment URL → gọi VNPayService
 *   - Xử lý VNPay callback / IPN
 *   - Cập nhật payment status + order payment_status
 *   - Idempotency: kiểm tra transaction_code trùng
 */
class PaymentService extends BaseService
{
    // TODO: Day 8
}
