<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SellerEarning;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function onboarding()
    {
        if (Auth::user()->is_seller) {
            return redirect()->route('seller.dashboard');
        }
        return view('seller.onboarding');
    }

    public function storeOnboarding(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'seller_type' => 'required|string',
            'business_name' => 'nullable|string|max:200',
            'gst_number' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->update([
            'is_seller' => true,
            'seller_type' => $request->seller_type,
            'business_name' => $request->business_name,
            'gst_number' => $request->gst_number,
        ]);

        return redirect()->route('seller.dashboard')->with('success', 'Seller account created successfully!');
    }

    public function index()
    {
        $seller = Auth::user();

        $totalProducts  = Product::where('user_id', $seller->id)->count();
        $activeProducts = Product::where('user_id', $seller->id)->where('status', 'active')->count();

        $newOrders = OrderItem::where('seller_id', $seller->id)
            ->where('status', 'confirmed')->count();

        $totalSales = SellerEarning::where('seller_id', $seller->id)
            ->whereIn('status', ['on_hold', 'released'])->sum('order_amount');

        $pendingEarnings = SellerEarning::where('seller_id', $seller->id)
            ->whereIn('status', ['pending', 'customer_ok', 'on_hold'])->sum('seller_amount');

        $releasedEarnings = SellerEarning::where('seller_id', $seller->id)
            ->where('status', 'released')->sum('seller_amount');

        $recentOrders = OrderItem::with(['order.buyer', 'product.images', 'earning'])
            ->where('seller_id', $seller->id)
            ->latest()
            ->limit(5)
            ->get();

        $availableEarnings = $releasedEarnings; // alias for view

        return view('seller.dashboard', compact(
            'totalProducts', 'activeProducts', 'newOrders',
            'totalSales', 'pendingEarnings', 'releasedEarnings', 'availableEarnings', 'recentOrders'
        ));
    }
}
