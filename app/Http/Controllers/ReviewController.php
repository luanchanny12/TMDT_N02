<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $user = $request->user();

        if ($user->reviews()->where('product_id', $product->id)->exists()) {
            return back()->withErrors(['comment' => 'Bạn đã đánh giá sản phẩm này rồi.']);
        }

        $hasPurchased = $user->orders()
            ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
            ->where('status', 'completed')
            ->exists();

        if (!$hasPurchased) {
            return back()->withErrors(['comment' => 'Bạn cần mua và nhận hàng sản phẩm này trước khi đánh giá.']);
        }

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image'   => 'nullable|image|max:2048',
        ], [
            'rating.required' => 'Vui lòng chọn số sao.',
            'rating.min'      => 'Số sao tối thiểu là 1.',
            'rating.max'      => 'Số sao tối đa là 5.',
            'image.image'     => 'File phải là hình ảnh.',
            'image.max'       => 'Hình ảnh tối đa 2MB.',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('reviews', 'public');
        }

        $validated['user_id']    = $user->id;
        $validated['product_id'] = $product->id;

        Review::create($validated);

        return back()->with('success', 'Đánh giá của bạn đã được gửi thành công!');
    }
}
