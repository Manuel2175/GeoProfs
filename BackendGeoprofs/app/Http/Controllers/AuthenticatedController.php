<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedController extends Controller
{
    /**
     * @OA\Get(
     *     path="/auth/request",
     *     summary="Get the currently logged-in user",
     *     tags={"Authentication"},
     *     @OA\Response(response=200, description="Authenticated user returned"),
     *     @OA\Response(response=401, description="No logged-in user")
     * )
     */
    public function getAuthenticatedUser()
    {
        if (Auth::check()) {
            return response()->json(Auth::user());
        }
        return response()->json()->setStatusCode(401);
    }

    /**
     * @OA\Post(
     *     path="/auth/request",
     *     summary="Login with username and password",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","password"},
     *             @OA\Property(property="name", type="string", example="testuser"),
     *             @OA\Property(property="password", type="string", format="password", example="Password123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Logged-in user returned"),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials)) {
            return response()->json(Auth::user());
        }

        return response()->json([
            'message' => 'Invalid username or password'
        ], 401);
    }

    /**
     * @OA\Delete(
     *     path="/auth/request",
     *     summary="Logout and invalidate session",
     *     tags={"Authentication"},
     *     @OA\Response(response=200, description="Logged out successfully"),
     *     @OA\Response(response=401, description="No logged-in user")
     * )
     */
    public function destroy(Request $request)
    {
        if (!Auth::check()) {
            return response()->json()->setStatusCode(401);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out']);
    }
}
