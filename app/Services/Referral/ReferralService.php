<?php

namespace App\Services\Referral;

use App\Services\BaseService;

/**
 * ReferralService — Day 9
 *
 * Business logic:
 *   - Tạo referral khi user đăng ký với referral_code
 *   - completeReferral(Order $order): void
 *       → Chỉ gọi khi order status = 'delivered'
 *       → Tính commission → cập nhật referral.status = 'completed'
 *   - Lấy lịch sử giới thiệu của user
 *   - Tổng commission của user
 */
class ReferralService extends BaseService
{
    // TODO: Day 9
}
