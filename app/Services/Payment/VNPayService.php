<?php

namespace App\Services\Payment;

use App\Services\BaseService;

/**
 * VNPayService — Day 8
 *
 * Tầng adapter thuần túy cho VNPay Sandbox API.
 * Không chứa business logic — chỉ build URL và verify signature.
 *
 * Business logic:
 *   - buildPaymentUrl(array $params): string
 *   - verifyCallback(array $vnpayData): bool
 *   - hashData(array $data): string (HMAC-SHA512)
 */
class VNPayService extends BaseService
{
    // TODO: Day 8
}
