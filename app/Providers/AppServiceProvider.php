<?php

namespace App\Providers;

use App\Services\SettingsService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('frontend.*', function ($view) {
            $view->with('settings', app(SettingsService::class)->all());
        });
    }
}
