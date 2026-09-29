<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        return view('wallet.index');
    }

    public function topup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        // Simulating a successful top-up for the buyer's wallet
        $user = Auth::user();
        $user->increment('wallet_balance', $request->amount);

        return back()->with('success', 'Wallet topped up successfully with ₹' . number_format($request->amount, 2));
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        if ($request->amount > $user->seller_wallet_balance) {
            return back()->with('error', 'Insufficient seller wallet balance.');
        }

        $user->decrement('seller_wallet_balance', $request->amount);

        // Save withdraw request logic here (e.g., creating a Payout record)
        \App\Models\Payout::create([
            'seller_id'       => $user->id,
            'amount'          => $request->amount,
            'status'          => 'pending',
            'processed_at'    => null,
            'transaction_ref' => 'WD-' . strtoupper(\Illuminate\Support\Str::random(10)),
        ]);

        return back()->with('success', 'Withdrawal request of ₹' . number_format($request->amount, 2) . ' submitted successfully.');
    }
}
