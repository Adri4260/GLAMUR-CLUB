<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- 1. Importante añadir esto arriba

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 2. Forzamos HTTPS si estamos en producción
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
