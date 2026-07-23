<?php

namespace App\Providers;

use App\Models\Office;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // O grid de oficinas é usado em várias páginas; injeta os dados automaticamente.
        View::composer('partials.offices-grid', function ($view) {
            if (! isset($view->getData()['offices'])) {
                $view->with('offices', Office::active()->get());
            }
        });
    }
}
