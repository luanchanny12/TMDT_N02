<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
    ];

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Tổng giá trị giỏ hàng (VNĐ).
     * Dùng method thường, không phải accessor — đây là calculation.
     */
    public function totalPrice(): int
    {
        return $this->items->sum(
            fn ($item) => $item->quantity * $item->product->effectivePrice()
        );
    }

    public function totalItems(): int
    {
        return $this->items->sum('quantity');
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
