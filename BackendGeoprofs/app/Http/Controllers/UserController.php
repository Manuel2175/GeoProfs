<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/user",
     *     summary="Get users",
     *     security={{"BearerAuth": {}}},
     *     tags={"User"},
     *     @OA\Response(response=200, description=" gebruikers teruggegeven"),
     *     @OA\Response(response=401, description="geen gebruikers"),
     *     @OA\Response(response=403, description="niet geauthorizeerd")
     * )
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //silence is golden
    }

    /**
     * @OA\Get(
     *     path="/user/{user}",
     *     summary="Get specific user",
     *     @OA\Parameter(
     *          name="user",
     *          in="path",
     *          required=true,
     *          description="uniquee indentificeerder van gebruiker",
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *     security={{"BearerAuth": {}}},
     *     tags={"User"},
     *     @OA\Response(response=401, description="geen gebruikers"),
     * *   @OA\Response(response=403, description="niet geauthorizeerd")
     * )
     */
    public function show(User $user)
    {
        return response()->json($user);
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
