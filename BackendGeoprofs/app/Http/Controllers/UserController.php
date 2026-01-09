<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
     * @OA\Put(
     *     path="/user/{user}",
     *     summary="Update User",
     *     security={{"BearerAuth": {}}},
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John"),
     *             @OA\Property(property="surname", type="string", example="Doe"),
     *             @OA\Property(property="verlofsaldo", type="number", format="float", example=44),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(
     *                 property="role",
     *                 type="string",
     *                 enum={"worker", "HR", "Manager"},
     *                 example="HR"
     *             ),
     *             @OA\Property(property="afdeling", type="string", example="Drone & Imaging")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Succesvol geüpdatet"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     )
     * )
     */

    public function update(Request $request, User $user)
    {

        $validated = $request->validate([
            'name' => 'string|max:255',
            'surname' => 'string|max:255',
            'password' => 'string|min:8',
            'verlofsaldo' => 'numeric',
        ]);

        $user->update($validated);

        return response()->json($user);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
