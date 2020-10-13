<?php

namespace App\Http\Middleware;

use Closure;

class IpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $ip = config('cowboys.ip_whitelist');

        if (!in_array($request->ip(), $ip)) {
            return response()->json(['error' => 401, 'message' => 'Unauthorized action.'], 401);
        }
        return $next($request);
    }
}
