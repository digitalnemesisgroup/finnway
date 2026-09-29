<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('hub.portal.login');
        }

        $user = Auth::user();

        // Allow both Payment Admin and System Admin to access payment control panel
        if ($user->isPaymentAdmin() || $user->isAdmin()) {
            return $next($request);
        }

        abort(403, 'Access denied.');
    }
}

