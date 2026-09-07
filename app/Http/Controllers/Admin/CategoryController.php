<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'image'      => 'nullable|image|max:1024',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Thêm danh mục thành công!');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'image'      => 'nullable|image|max:1024',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Không thể xóa danh mục đang có sản phẩm! Hãy chuyển sản phẩm sang danh mục khác trước.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Đã xóa danh mục!');
    }
}
