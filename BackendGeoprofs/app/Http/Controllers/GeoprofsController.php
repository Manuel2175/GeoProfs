<?php

namespace App\Http\Controllers;

use App\Models\Rooster_week;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class GeoprofsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/geoprofs/aanwezigen",
     *     summary="Alle aanwezige op kantoor van de huidige dag opvragen in getal",
     *     security={{"BearerAuth": {}}},
     *     tags={"Geoprofs"},
     *     @OA\Response(
     *         response=200,
     *         description="Aanwezige leden opgehaald "
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Geen aanwezige leden gevonden"
     *     )
     * )
     */
    // tonen specifieke verlofaanvraag
    public function aanwezigen()
    {
        $users = User::all();
        $usersAvailable = 0;
        foreach ($users as $user) {
            $vandaag = $user->vandaag();
            if ($vandaag && $vandaag->ochtend && $vandaag->middag) {
                $usersAvailable++;
            }
        }
        return response()->json($usersAvailable);
    }

    /**
     * @OA\Get(
     *     path="/geoprofs/afwezigen",
     *     summary="Alle afwezige op kantoor van de huidige dag opvragen in getal",
     *     security={{"BearerAuth": {}}},
     *     tags={"Geoprofs"},
     *     @OA\Response(
     *         response=200,
     *         description="Afwezige leden opgehaald "
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Geen afwezige leden gevonden"
     *     )
     * )
     */
    // tonen specifieke verlofaanvraag
    public function afwezigen()
    {
        $users = User::all();
        $usersUnavailable = 0;
        foreach ($users as $user) {
            $vandaag = $user->vandaag();
            if ($vandaag && !$vandaag->ochtend && !$vandaag->middag) {
                $usersUnavailable++;
            }
        }
        return response()->json($usersUnavailable);
    }
}
