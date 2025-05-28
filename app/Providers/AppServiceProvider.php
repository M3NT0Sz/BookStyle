<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Não é mais necessário fazer bind da interface BookFactoryInterface
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
