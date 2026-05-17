<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserLastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('users')->check()) {
            $user = Auth::guard('users')->user();
            if (is_null($user->last_seen_at) || now()->diffInMinutes($user->last_seen_at) >= 2) {
                $user->updateQuietly(['last_seen_at' => now()]);
            }
        }

        return $next($request);
    }
}
