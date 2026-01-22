<?php

namespace App\Providers;

use App\Listeners\VerstuurBevestigingVerlofAangevraagd;
use App\Models\User;
use App\Models\VerlofAanvraag;
use App\Notifications\VerlofAangevraagdNotification;
use App\Observers\UserObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        VerstuurBevestigingVerlofAangevraagd::class => [
            VerlofAangevraagdNotification::class,
        ]
    ];

    public function boot(): void
    {
        User::observe(UserObserver::class);
    }
}
