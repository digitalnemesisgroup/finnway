<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PaymentClient;
use Illuminate\Support\Facades\Auth;

class PaymentClientPortalMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check explicit session assignment
        $clientId = session('payment_client_id');
        if ($clientId) {
            $client = PaymentClient::find($clientId);
            if ($client) {
                // Attach client instance to request attributes for easy controller access
                $request->attributes->set('payment_client', $client);
                return $next($request);
            }
        }

        // 2. Fallback check: Logged-in user has an associated PaymentClient account
        if (Auth::check()) {
            $user = Auth::user();
            $client = PaymentClient::where('user_id', $user->id)->first();
            if ($client) {
                session(['payment_client_id' => $client->id]);
                $request->attributes->set('payment_client', $client);
                return $next($request);
            }

            // Admins can inspect portal using client #1 or first approved client if available
            if ($user->isAdmin() || $user->isPaymentAdmin()) {
                $client = PaymentClient::first();
                if ($client) {
                    session(['payment_client_id' => $client->id]);
                    $request->attributes->set('payment_client', $client);
                    return $next($request);
                }
            }
        }

        return redirect()->route('hub.portal.login')->with('error', 'Please log in with your business credentials to access your Payment Hub Portal.');
    }
}

