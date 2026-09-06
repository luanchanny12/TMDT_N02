<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Thứ tự quan trọng: phải seed đúng thứ tự FK dependency.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,       // 1. Users trước (FK gốc)
            CategorySeeder::class,   // 2. Categories (Product phụ thuộc)
            ProductSeeder::class,    // 3. Products + ProductImages
            CouponSeeder::class,     // 4. Coupons (không phụ thuộc gì)
        ]);
    }
}
