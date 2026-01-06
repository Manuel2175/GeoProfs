<?php

namespace App\Providers;

use App\Models\VerlofAanvraag;
use App\Observers\VerlofAanvraagObserver;
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
        VerlofAanvraag::observe(VerlofAanvraagObserver::class);
    }
}
