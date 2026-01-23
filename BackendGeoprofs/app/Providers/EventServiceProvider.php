<?php

namespace App\Providers;

use App\Models\User;
use App\Models\VerlofAanvraag;
use App\Observers\UserObserver;
use App\Observers\VerlofAanvraagObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        //
    ];

    public function boot(): void
    {
        User::observe(UserObserver::class);
    }
}
