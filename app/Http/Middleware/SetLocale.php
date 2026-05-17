<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // API routes: read Accept-Language header or ?lang= query param
        if ($request->is('api/*')) {
            $locale = $request->query('lang')
                   ?? strtolower(substr($request->header('Accept-Language', 'en'), 0, 2));
        } else {
            // Web: read from session, fallback to app default
            $locale = session('locale', config('app.locale'));
        }

        if (in_array($locale, ['en', 'ar'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
