<?php
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if(!function_exists('decodeJwtUser')){

    function decodeJwtUser($jwt){
        if (!$jwt || $jwt == 'null' || $jwt == '') {
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

        return $user;
    }
}
