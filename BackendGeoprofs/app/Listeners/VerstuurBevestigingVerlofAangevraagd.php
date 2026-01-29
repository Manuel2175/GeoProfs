<?php

namespace App\Listeners;

use App\Events\VerlofAangevraagd;
use App\Models\User;
use App\Notifications\VerlofAangevraagdNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class VerstuurBevestigingVerlofAangevraagd
{
    /**
     * Handle the event.
     */
    public function handle(VerlofAangevraagd $event): void
    {
        $users = User::whereIn('role', ['worker', 'admin'])->get();
        foreach ($users as $user) {
            $user->notify(new VerlofAangevraagdNotification(
                $event->verlofAanvraag,
            ));
        }
    }
}
