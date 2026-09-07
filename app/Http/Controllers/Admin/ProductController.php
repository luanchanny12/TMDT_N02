<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'images');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'sale_price'    => 'nullable|numeric|min:0|lt:price',
            'category_id'   => 'required|exists:categories,id',
            'stock'         => 'required|integer|min:0',
            'brand'         => 'nullable|string',
            'image'         => 'nullable|image|max:2048',
            'status'        => 'sometimes|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $request->input('status', 'active');

        Product::create(collect($validated)->only(['name', 'slug', 'description', 'price', 'sale_price', 'category_id', 'stock', 'brand', 'status'])->toArray());

        return redirect()->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'sale_price'    => 'nullable|numeric|min:0|lt:price',
            'category_id'   => 'required|exists:categories,id',
            'stock'         => 'required|integer|min:0',
            'brand'         => 'nullable|string',
            'image'         => 'nullable|image|max:2048',
            'status'        => 'sometimes|in:active,inactive,out_of_stock',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $product->update(collect($validated)->only(['name', 'slug', 'description', 'price', 'sale_price', 'category_id', 'stock', 'brand', 'status'])->toArray());

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Đã xóa sản phẩm!');
    }

    public function toggleActive(Product $product)
    {
        $newStatus = $product->status === 'active' ? 'inactive' : 'active';
        $product->update(['status' => $newStatus]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $product->fresh()->status,
            ]);
        }

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}
