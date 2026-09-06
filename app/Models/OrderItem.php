<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',  // snapshot
        'price',         // snapshot giá tại thời điểm mua
        'quantity',
        'subtotal',      // price * quantity
    ];

    protected function casts(): array
    {
        return [
            'price'    => 'integer',
            'quantity' => 'integer',
            'subtotal' => 'integer',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
