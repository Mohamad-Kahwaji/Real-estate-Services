<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Middleware\PermissionMiddleware;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission, ?string $guard = null)
    {
        if (auth('superadmins')->check()) {
            return $next($request);
        }

        return app(PermissionMiddleware::class)->handle($request, $next, $permission, $guard);
    }
}
