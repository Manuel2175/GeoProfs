<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle(Request $request, Closure $next): Response
{
    $user = auth()->user();

    if (!$user) {
        abort(403, 'Unauthorized.');
    }

    if ($user->admin()) {
        // admin mag alles
        return $next($request);
    }

    if ($user->manager()) {
        // managers krijgen alleen workers
        return response()->json(User::where('role', 'worker')->get());
    }

    if ($user->HR()) {
        // HR mag door
        return $next($request);
    }

    // alles anders → geen toegang
    abort(403, 'Unauthorized.');
}

}
