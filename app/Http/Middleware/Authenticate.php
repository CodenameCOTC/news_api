<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function handle($request, Closure $next, ...$guard)
    {
        $response = [
            'message' => 'Unauthorized'
        ];

        if (!$request->expectsJson()) {
            return response()->json($response, 401);
        }

        return $next($request);
    }
}
