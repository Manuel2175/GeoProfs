<?php

namespace App\Http\Controllers;

use App\Models\Rooster_week;
use App\Models\User;
use Illuminate\Http\Request;

class RoosterWeekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/user/{user}/rooster_week/{rooster_week}",
     *     summary="Verkrijgen rooster van een specifieke week van een gebruiker",
     *     security={{"BearerAuth": {}}},
     *     tags={"Rooster"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="rooster_week",
     *         in="path",
     *         required=true,
     *         description="ID of the rooster_week",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Weekrooster retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Weekrooster not found"
     *     )
     * )
     */
    // tonen specifieke rooster van een week
    public function show(User $user , Rooster_week $rooster_week)
    {
        $rooster_week->load('dagen');
        return response()->json($rooster_week);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rooster_week $rooster_week)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rooster_week $rooster_week)
    {
        //
    }
}
