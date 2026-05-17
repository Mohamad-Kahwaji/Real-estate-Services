<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminLastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admins')->check()) {
            $admin = Auth::guard('admins')->user();
            // Update every 5 minutes max to avoid excessive DB writes
            if (is_null($admin->last_seen_at) || now()->diffInMinutes($admin->last_seen_at) >= 5) {
                $admin->updateQuietly(['last_seen_at' => now()]);
            }
        }

        return $next($request);
    }
}
