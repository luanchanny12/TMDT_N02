<?php

namespace App\Http\Controllers;

use App\Services\Product\ProductService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    /**
     * Danh sách sản phẩm với bộ lọc và phân trang.
     *
     * @group Products
     * @unauthenticated
     *
     * @queryParam search string Từ khoá tìm kiếm theo tên / SKU. Example: áo
     * @queryParam category_id int ID danh mục. Example: 1
     * @queryParam min_price int Giá tối thiểu (VNĐ). Example: 50000
     * @queryParam max_price int Giá tối đa (VNĐ). Example: 500000
     * @queryParam sort string Sắp xếp: newest | oldest | price_asc | price_desc. Example: price_asc
     * @queryParam per_page int Số sản phẩm mỗi trang (mặc định 15). Example: 12
     */
    public function index(Request $request): View
    {
        $filters    = $request->only(['search', 'category_id', 'min_price', 'max_price', 'sort']);
        $perPage    = (int) $request->get('per_page', 15);
        $products   = $this->productService->getProducts($filters, $perPage);
        $categories = $this->productService->getCategories();

        return view('products.index', compact('products', 'categories', 'filters'));
    }

    /**
     * Chi tiết sản phẩm theo slug.
     *
     * @group Products
     * @unauthenticated
     *
     * @urlParam slug string required Slug của sản phẩm. Example: ao-thun-nam-cao-cap
     */
    public function show(string $slug): View
    {
        $product = $this->productService->getBySlug($slug);

        return view('products.show', compact('product'));
    }
}
