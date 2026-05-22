<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'admin.lastseen' => \App\Http\Middleware\AdminLastSeen::class,
            'user.lastseen'  => \App\Http\Middleware\UserLastSeen::class,
        ]);

        $middleware->appendToGroup('web', \App\Http\Middleware\SetLocale::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\AdminLastSeen::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\UserLastSeen::class);
        $middleware->appendToGroup('api', \App\Http\Middleware\SetLocale::class);
        $middleware->appendToGroup('api', \App\Http\Middleware\UserLastSeen::class);

        $middleware->redirectUsersTo(function (Request $request) {
            if (Auth::guard('superadmins')->check()) {
                return route('dashboard-analytics');
            }
            if (Auth::guard('admins')->check()) {
                return route('admin.dashboard');
            }
            return route('dashi');
        });

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->expectsJson()) {
                return null;
            }

            $route = $request->route();

            if ($route && in_array('auth:superadmins', $route->middleware())) {
                return route('loginsa.create');
            }

            if ($route && in_array('auth:admins', $route->middleware())) {
                return route('logina.create');
            }

            if ($route && in_array('auth:users', $route->middleware())) {
                return route('login');
            }

            return route('login');
        });
    })
    ->withBroadcasting(
         __DIR__.'/../routes/channels.php'
    )
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
