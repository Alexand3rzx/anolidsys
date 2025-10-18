<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->usertype === 'admin') {
            return $next($request);
        }

        // If not admin, redirect to home with error
        return redirect()->route('home')->with('error', 'Access denied.');
    }
}
