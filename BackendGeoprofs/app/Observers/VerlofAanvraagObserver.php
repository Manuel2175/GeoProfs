<?php

namespace App\Observers;

use App\Models\Rooster_dag;
use App\Models\Rooster_week;
use App\Models\VerlofAanvraag;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Date;

class VerlofAanvraagObserver
{
    /**
     * Handle the VerlofAanvraag "created" event.
     */
    public function created(VerlofAanvraag $verlofAanvraag): void
    {
        //
    }

    /**
     * Handle the VerlofAanvraag "updated" event.
     */
    public function updated(VerlofAanvraag $verlofAanvraag): void
    {
        if ($verlofAanvraag->wasChanged('status') && $verlofAanvraag->status === 'goedgekeurd')
        {
            $startDatum = Carbon::parse($verlofAanvraag->startdatum);
            $eindDatum = Carbon::parse($verlofAanvraag->einddatum);
            $periode = CarbonPeriod::create($startDatum, $eindDatum);
            Carbon::setLocale('nl');
            foreach ($periode as $datum) {
                if ($datum->isWeekend()) {
                    continue;
                }
                $currentWeek = $datum->weekOfYear;
                $week = Rooster_week::with('dagen')->where('weeknummer', $currentWeek)->first();
                if (!$week)
                {
                    continue;
                }
                $dagNaam = $datum->translatedFormat('l');
                foreach ($week->dagen as $dag) {
                    if ($dag->name === $dagNaam) {
                        $dag->update([
                            'ochtend' => false,
                            'middag' => false,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Handle the VerlofAanvraag "deleted" event.
     */
    public function deleted(VerlofAanvraag $verlofAanvraag): void
    {
        //
    }

    /**
     * Handle the VerlofAanvraag "restored" event.
     */
    public function restored(VerlofAanvraag $verlofAanvraag): void
    {
        //
    }

    /**
     * Handle the VerlofAanvraag "force deleted" event.
     */
    public function forceDeleted(VerlofAanvraag $verlofAanvraag): void
    {
        //
    }
}
