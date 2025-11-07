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
        if (auth()->check() && auth()->user()->admin() == "admin") {
           return $next($request);
        }
        elseif (auth()->check() && auth()->user()->manager() == "manager") {
            return response()->json(User::where('role', 'worker')->get());
        }
        elseif (auth()->check() && auth()->user()->HR() == "HR") {
            return $next($request);
        }else{
            abort(403, 'Unauthorized.');
        }

    }
}
