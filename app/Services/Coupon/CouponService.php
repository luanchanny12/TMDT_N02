<?php

namespace App\Services\Coupon;

use App\Services\BaseService;

/**
 * CouponService — Day 9
 *
 * Business logic:
 *   - validateCoupon(string $code, User $user, int $orderAmount): array
 *       → Kiểm tra tồn tại, valid(), user chưa dùng, đơn tối thiểu
 *       → Trả về discount amount
 *   - applyCoupon(Coupon $coupon, Order $order, User $user): CouponUsage
 *       → Tạo CouponUsage + tăng used_count
 *   - Danh sách coupon hợp lệ (admin)
 */
class CouponService extends BaseService
{
    // TODO: Day 9
}
