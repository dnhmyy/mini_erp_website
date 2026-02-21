<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        Paginator::useTailwind();
        Paginator::defaultView('vendor.pagination.minimal');
        Paginator::defaultSimpleView('vendor.pagination.minimal');
        
        // Limit the number of pagination links
        view()->composer('vendor.pagination.minimal', function ($view) {
            $paginator = $view->getData()['paginator'];
            if (method_exists($paginator, 'onEachSide')) {
                $paginator->onEachSide(1);
            }
        });
        
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
