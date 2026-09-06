<?php

namespace App\Services\Order;

use App\Services\BaseService;

/**
 * OrderService — Day 7
 *
 * Business logic (use case chính):
 *   - createOrder(User $user, array $data): Order
 *       1. Kiểm tra cart không rỗng
 *       2. Kiểm tra stock từng sản phẩm
 *       3. Tính subtotal
 *       4. Áp coupon (nếu có) → gọi CouponService
 *       5. Tính shipping_fee
 *       6. Tính total
 *       7. Tạo Order + OrderItems (snapshot name + price)
 *       8. Trừ stock
 *       9. Clear cart
 *      10. Tạo Payment record (pending)
 *      11. Trigger referral check → gọi ReferralService
 *   - cancelOrder(Order $order): void
 *   - updateStatus(Order $order, string $status): void
 *   - Danh sách đơn hàng của user / admin
 */
class OrderService extends BaseService
{
    // TODO: Day 7
}
