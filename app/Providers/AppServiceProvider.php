<?php

namespace App\Providers;

use App\Models\Superadmin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Superadmins bypass all gate / Spatie permission checks
        Gate::before(function ($user, $ability) {
            if ($user instanceof Superadmin) {
                return true;
            }
        });
    }
}
