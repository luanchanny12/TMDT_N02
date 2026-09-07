<?php

namespace App\Services\Product;

use App\Models\Category;
use App\Models\Product;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductService extends BaseService
{
    /**
     * Lấy danh sách sản phẩm active, có bộ lọc và phân trang.
     *
     * Filters nhận:
     *   - search      : string   — tìm kiếm theo tên / SKU
     *   - category_id : int      — lọc theo danh mục
     *   - min_price   : int      — giá tối thiểu (theo effectivePrice)
     *   - max_price   : int      — giá tối đa
     *   - sort        : string   — newest | oldest | price_asc | price_desc
     */
    public function getProducts(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Product::with([
            'images' => fn ($q) => $q->where('is_primary', true)->limit(1),
            'category:id,name,slug',
        ])->active();

        // Tìm kiếm
        if (!empty($filters['search'])) {
            $keyword = '%' . $filters['search'] . '%';
            $query->where(fn ($q) => $q->where('name', 'like', $keyword)
                                       ->orWhere('sku', 'like', $keyword));
        }

        // Lọc danh mục
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Lọc giá (dùng COALESCE để ưu tiên sale_price)
        $priceExpr = 'COALESCE(sale_price, price)';
        if (!empty($filters['min_price'])) {
            $query->whereRaw("$priceExpr >= ?", [$filters['min_price']]);
        }
        if (!empty($filters['max_price'])) {
            $query->whereRaw("$priceExpr <= ?", [$filters['max_price']]);
        }

        // Sắp xếp
        match ($filters['sort'] ?? 'newest') {
            'price_asc'  => $query->orderByRaw("$priceExpr ASC"),
            'price_desc' => $query->orderByRaw("$priceExpr DESC"),
            'oldest'     => $query->orderBy('created_at', 'asc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Lấy chi tiết 1 sản phẩm qua slug.
     * Throw ModelNotFoundException nếu không tìm thấy.
     */
    public function getBySlug(string $slug): Product
    {
        return Product::with([
            'images',
            'category:id,name,slug',
            'reviews' => fn ($q) => $q->latest()->limit(10),
            'reviews.user:id,name,avatar',
        ])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /**
     * Lấy sản phẩm nổi bật cho trang chủ.
     */
    public function getFeatured(int $limit = 8): Collection
    {
        return Product::with([
            'images' => fn ($q) => $q->where('is_primary', true)->limit(1),
        ])
            ->active()
            ->inStock()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Lấy tất cả danh mục (cho dropdown bộ lọc).
     */
    public function getCategories(): Collection
    {
        return Category::select('id', 'name', 'slug')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }
}
