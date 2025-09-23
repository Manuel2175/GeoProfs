<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function Pest\Laravel\json;

class AuthenticatedController extends Controller
{
    /**
     * @OA\Get(
     *     path="/auth/request",
     *     summary="Verkrijg de huidige ingelogde user",
     *     tags={"Authentication"},
     *     @OA\Response(response=401, description="Geen ingelogde user"),
     * )
     */
    public function getAuthenticatedUser()
    {
        if (Auth::check()) {
            return response()->json(Auth::user());
        }
        else
        {
            return response()->json()->setStatusCode(401);
        }
    }
    /**
     * @OA\Delete(
     *     path="/auth/request",
     *     summary="Delete the authenticated session from the session storage",
     *     tags={"Authentication"},
     *     @OA\Response(response=401, description="Geen ingelogde user"),
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
        return response()->json([
            'message' => 'Logged out',
        ]);
    }
}
