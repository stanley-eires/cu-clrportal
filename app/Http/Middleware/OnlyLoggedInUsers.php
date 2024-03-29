<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OnlyLoggedInUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role): Response
    {
        if (!Auth::hasUser()) {
            return to_route('public.courses')->with('message', ['content' => 'Login is required to continue.', 'status' => 'error']);
        }
        // elseif (count(array_intersect($role, Auth::user()->roles)) === 0) {
        //     return back()->with('message', ['content' => 'You do not have the required permissions to access this page.', 'status' => 'error']);
        // }
        return $next($request);
    }
}
