<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Show review form for a delivered order item
    public function create(Request $request)
    {
        $productId = $request->product_id;
        $orderId   = $request->order_id;

        // Verify buyer actually purchased and received this product
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->where('status', 'delivered')
            ->firstOrFail();

        $product = Product::findOrFail($productId);

        // Check item belongs to this order
        $item = $order->items()->where('product_id', $productId)->firstOrFail();

        // Check no duplicate review
        $existing = Review::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->where('order_id', $orderId)
            ->first();

        return view('buyer.review-create', compact('order', 'product', 'item', 'existing'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // Prevent duplicate — one review per user per product
        $existing = Review::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        Review::create([
            'product_id'  => $request->product_id,
            'user_id'     => $user->id,
            'order_id'    => null,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
            'is_approved' => true,
        ]);

        // Update product average rating
        $product = Product::find($request->product_id);
        $avg     = Review::where('product_id', $product->id)->avg('rating');
        $count   = Review::where('product_id', $product->id)->count();
        $product->update(['rating' => round($avg, 2), 'rating_count' => $count]);

        return back()->with('success', 'Thank you for your review!');
    }
}
