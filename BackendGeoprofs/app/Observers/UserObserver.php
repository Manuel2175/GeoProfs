<?php

namespace App\Observers;

use App\Models\Rooster_dag;
use App\Models\Rooster_week;
use App\Models\User;
use Illuminate\Support\Facades\Date;

class UserObserver
{
    private $dagen = [
        "Maandag",
        "Dinsdag",
        "Woensdag",
        "Donderdag",
        "Vrijdag",
    ];

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $currentWeek = Date::now()->weekOfYear();
        for ($j = 0; $j < 10; $j++) {
            //Standaard rooster maken
            $rooster = Rooster_week::create([
                'weeknummer' => $currentWeek,
            ]);
            $user->roosters()->attach($rooster->id);
            for ($i = 0; $i < 5; $i++) {
                Rooster_dag::create([
                    "name" => $this->dagen[$i],
                    "rooster_weeks_id" => $rooster->id,
                ]);
            }
            if ($currentWeek == 52)
            {
                $currentWeek = 1;
            }
            else {
                $currentWeek++;
            }
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
