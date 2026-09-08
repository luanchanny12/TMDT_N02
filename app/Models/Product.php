<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'sku', 'description', 'price', 'sale_price',
        'stock', 'brand', 'status', 'category_id',
    ];

    protected function casts(): array
    {
        return [
            'price'       => 'integer',
            'sale_price'  => 'integer',
            'stock'       => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true)->orWhere(fn($q) => $q->orderBy('sort_order'));
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlist_items', 'product_id', 'user_id')->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->with('user')->latest();
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // ─── Accessors / Helpers ──────────────────────────────────────────────────

    public function effectivePrice(): int
    {
        return $this->sale_price ?? $this->price;
    }

    public function isOnSale(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    public function getImageUrlAttribute(): ?string
    {
        $img = $this->images()->where('is_primary', true)->first() ?? $this->images()->first();
        return $img ? asset($img->image_path) : null;
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }
}
