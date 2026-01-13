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

        // Geen ingelogde user → abort
        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        // Admin en HR mogen alles
       if (in_array(strtolower($user->role), ['admin', 'hr'])) {
           return $next($request);
       }

        // Manager mag alleen GET requests naar workers
        if ($user->role === 'manager') {
            if ($request->isMethod('GET')) {
                return response()->json(User::where('role', 'worker')->get());
            } else {
                abort(403, 'Unauthorized.');
            }
        }

        // Alle andere gevallen → geen toegang
        abort(403, 'Unauthorized.');
    }
}
