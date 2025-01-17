<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StandardMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the authenticated user's subscription level is 'standard'
        if (auth()->check() && auth()->user()->subscription->level === 'standard') {
            return $next($request);
        }

        // If not authorized, redirect or return an error response
        return response()->json([
            'status' => 'error',
            'code' => 403,
            'message' => 'Access denied: Standard users only.',
        ], 403);
    }
}
