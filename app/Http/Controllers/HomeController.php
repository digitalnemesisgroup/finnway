<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        $recommended = Product::with(['images', 'seller', 'category'])
            ->where('status', 'active')
            ->orderByDesc('rating')
            ->orderByDesc('view_count')
            ->limit(12)
            ->get();

        $categoryGroups = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get()
            ->map(function($category) {
                $childIds = $category->children()->pluck('id')->toArray();
                $allCategoryIds = array_merge([$category->id], $childIds);

                $category->top_products = Product::with(['images', 'seller'])
                    ->where('status', 'active')
                    ->whereIn('category_id', $allCategoryIds)
                    ->latest()
                    ->limit(8)
                    ->get();
                return $category;
            })->filter(function($category) {
                return $category->top_products->isNotEmpty();
            })->take(10); // Show up to 10 groups

        $cartCount = 0;
        if (Auth::check()) {
            $cart = Auth::user()->cart()->with('items')->first();
            $cartCount = $cart ? $cart->items_count : 0;
        }

        return view('home', compact('categories', 'recommended', 'categoryGroups', 'cartCount'));
    }
}
