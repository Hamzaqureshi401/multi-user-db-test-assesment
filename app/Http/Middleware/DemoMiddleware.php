<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DemoMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        dd(auth()->user());
        // Check if the authenticated user's subscription level is 'demo'
        if (auth()->check() && auth()->user()->subscription->level === 'demo') {
            return $next($request);
        }

       return response()->json([
            'status' => 'error',
            'code' => 403,
            'message' => 'Access denied. You do not have the required permissions.',
        ], 403);
    }
}
