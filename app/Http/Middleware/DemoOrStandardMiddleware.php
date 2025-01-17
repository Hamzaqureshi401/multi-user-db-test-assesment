<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DemoOrStandardMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && in_array($user->subscription->level, ['demo', 'standard'])) {
            return $next($request);
        }

        return response()->json([
            'status' => 'error',
            'code' => 403,
            'message' => 'Access denied. You do not have the required permissions.',
        ], 403);
    }
}
