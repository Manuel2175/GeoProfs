<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use function Pest\Laravel\json;

class UserController extends Controller
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
     *     path="/user/{user}",
     *     summary="Get a specific user object",
     *     security={{"BearerAuth": {}}},
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Verlofaanvraag retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Gebruiker is een admin of hr medewerker.!"
     *     )
     * )
     */
    public function show(User $user)
    {
        if ($user->role != "admin" || $user->role != "HR") {
            return response()->json($user);
        } else {
            return response()->json([
                'message' => 'Gebruiker is een admin of hr medewerker!'
            ], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
