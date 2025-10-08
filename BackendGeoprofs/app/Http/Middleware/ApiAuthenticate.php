<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = 'sanctum'): Response
    {
        if (!Auth::guard($guard)->check()) {
            return response()->json(['message' => 'You need to be authorized to perform this action .'], 401);
        }
        return $next($request);
    }
}
