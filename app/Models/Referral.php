<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    protected $fillable = [
        'referrer_id',
        'referred_user_id',
        'order_id',
        'referral_code',
        'commission',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'commission' => 'integer',
        ];
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * User A — người giới thiệu.
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * User B — người được giới thiệu.
     */
    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    /**
     * Đơn hàng kích hoạt commission (delivered thì mới tính).
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
