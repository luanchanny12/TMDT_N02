<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $products = Product::whereHas('wishlistedBy', function ($q) {
            $q->where('user_id', Auth::id());
        })->latest()->paginate(12);

        $wishlistIds = Auth::user()->wishlists()->pluck('product_id')->toArray();

        return view('pages.wishlist', compact('products', 'wishlistIds'));
    }

    public function toggle(Request $request, Product $product)
    {
        $user = Auth::user();
        $exists = Wishlist::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($exists) {
            $exists->delete();
            $isWishlisted = false;
            $message = 'Đã bỏ yêu thích';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $isWishlisted = true;
            $message = 'Đã thêm vào yêu thích';
        }

        return response()->json([
            'success' => true,
            'is_wishlisted' => $isWishlisted,
            'message' => $message,
        ]);
    }
}
