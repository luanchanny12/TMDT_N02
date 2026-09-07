<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with('category')
            ->where('status', 'active')
            ->latest()
            ->limit(8)
            ->get();

        $newProducts = Product::with('category')
            ->where('status', 'active')
            ->latest()
            ->limit(8)
            ->get();

        $categories = Category::withCount('products')
            ->where('status', 'active')
            ->get();

        return view('pages.home', compact('featuredProducts', 'newProducts', 'categories'));
    }
}
