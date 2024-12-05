<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BuyerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = decodeJwtUser($request->bearerToken());

        if (!in_array($user->role, ['buyer'])) {
            return response()->json([
                'success' => false,
                'message' => 'fail_auth_role',
            ], 401);
        }

        $request->merge(['user' => $user]);
        return $next($request);
    }
}
