<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;

class AuthenticatedController extends Controller
{
    /**
     * @OA\Post(
     *     path="/auth/request",
     *     summary="Login with username and password (returns Sanctum token)",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","password"},
     *             @OA\Property(property="name", type="string", example="Brycen Veum"),
     *             @OA\Property(property="password", type="string", format="password", example="Password123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login successful, returns user and token"),
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

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid username or password'], 401);
        }

        $user = Auth::user();

        // Create Sanctum token
        $token = $user->createToken('auth-token')->plainTextToken;

        Log::channel('daily')->info('User logged in', [
            'user_id' => $user->id,
            'username' => $user->name,
            'ip' => $request->ip(),
            'time' => now()->toDateTimeString(),
        ]);
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/auth/request",
     *     summary="Get the currently authenticated user (requires Bearer token)",
     *     security={{"BearerAuth": {}}},
     *     tags={"Authentication"},
     *     @OA\Response(response=200, description="Authenticated user returned"),
     *     @OA\Response(response=401, description="Not authenticated")
     * )
     */
    public function getAuthenticatedUser(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * @OA\Delete(
     *     path="/auth/request",
     *     summary="Logout and revoke the current Sanctum token",
     *     security={{"BearerAuth": {}}},
     *     tags={"Authentication"},
     *     @OA\Response(response=200, description="Token revoked successfully"),
     *     @OA\Response(response=401, description="Not authenticated")
     * )
     */
    public function logout(Request $request)
    {
        Log::channel('daily')->info('User logged in', [
            'user_id' => $request->user()->id,
            'username' => $request->user()->name,
            'ip' => $request->ip(),
            'time' => now()->toDateTimeString(),
        ]);
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
