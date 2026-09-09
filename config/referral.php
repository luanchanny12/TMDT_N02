<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Referral Commission Percentage
    |--------------------------------------------------------------------------
    |
    | Phần trăm hoa hồng mặc định mà người giới thiệu nhận được
    | dựa trên tổng giá trị đơn hàng đầu tiên của người được giới thiệu.
    |
    */
    'commission_percent' => env('REFERRAL_COMMISSION_PERCENT', 5),

    /*
    |--------------------------------------------------------------------------
    | Referral Cookie Name & Lifetime
    |--------------------------------------------------------------------------
    |
    | Tên Cookie dùng để lưu trữ mã giới thiệu và thời gian sống của Cookie.
    | Thời gian sống mặc định là 30 ngày (tính bằng phút: 30 * 24 * 60 = 43200).
    |
    */
    'cookie_name' => 'referral_code',
    'cookie_lifetime' => 43200, // 30 days in minutes
];
