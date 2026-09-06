<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        // Coupon cố định để test
        Coupon::create([
            'code'             => 'WELCOME10',
            'type'             => 'percent',
            'value'            => 10,
            'min_order_amount' => 100000,
            'max_discount'     => 50000,
            'start_at'         => now()->subDay(),
            'end_at'           => now()->addYear(),
            'usage_limit'      => null, // không giới hạn
            'used_count'       => 0,
            'status'           => 'active',
        ]);

        Coupon::create([
            'code'             => 'FREESHIP',
            'type'             => 'fixed',
            'value'            => 30000,   // giảm 30K = phí ship
            'min_order_amount' => 200000,
            'max_discount'     => null,
            'start_at'         => now()->subDay(),
            'end_at'           => now()->addMonths(3),
            'usage_limit'      => 100,
            'used_count'       => 0,
            'status'           => 'active',
        ]);

        Coupon::create([
            'code'             => 'SALE50',
            'type'             => 'percent',
            'value'            => 50,
            'min_order_amount' => 500000,
            'max_discount'     => 200000,
            'start_at'         => now()->subDay(),
            'end_at'           => now()->addDays(7),
            'usage_limit'      => 50,
            'used_count'       => 0,
            'status'           => 'active',
        ]);

        // 2 coupon random thêm
        Coupon::factory()->count(2)->create();
    }
}
