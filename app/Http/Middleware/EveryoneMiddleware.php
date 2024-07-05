<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class EveryoneMiddleware
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
        $jwt = $request->bearerToken();

        if ($jwt == 'null' || $jwt == '') {
            return response()->json([
                'success' => false,
                'message' => 'fail_auth_token',
            ], 401);
        }

        $jwtDecoded = JWT::decode($jwt, new Key(env('JWT_SECRET_KEY'), 'HS256'));

        $user = User::find($jwtDecoded->sub);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'fail_get_user_notfound',
            ], 401);
        }

        if (!$user->role) {
            return response()->json([
                'success' => false,
                'message' => 'fail_auth_token',
            ], 401);
        }

        $request->merge(['user' => $user]);
        return $next($request);
    }
}
