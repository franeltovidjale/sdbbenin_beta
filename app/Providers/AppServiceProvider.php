<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
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
    Schema::defaultStringLength(191);
    
    // Méthode alternative pour définir le template de pagination
    Paginator::useTailwind();  // D'abord, utilisez Tailwind comme base
    
    // Puis écrasez les vues par défaut
    $this->loadViewsFrom(resource_path('views/vendor/pagination'), 'pagination');
}
}