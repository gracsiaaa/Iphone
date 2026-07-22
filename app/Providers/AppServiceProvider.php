<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useTailwind();

        try {
            if (Schema::hasTable('settings')) {
                View::share('siteSettings', Setting::pluck('value', 'key'));
            }
        } catch (Throwable) {
            View::share('siteSettings', collect());
        }
    }
}
