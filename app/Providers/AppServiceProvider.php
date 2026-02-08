<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         // ✅ Set timezone WIB (dari fix sebelumnya)
        config(['app.locale' => 'id']);
        \Carbon\Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        // ✅ FORCE HTTPS untuk Ngrok/Production
        if (
            request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
            str_contains(request()->server('HTTP_HOST'), 'ngrok')
        ) {
            URL::forceScheme('https');
        }
    }
    
}
