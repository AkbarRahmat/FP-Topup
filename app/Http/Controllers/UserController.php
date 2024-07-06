<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function generate(Request $request) {
        $input = $request->validate([
            'global_id' => 'required|numeric',
            'role' => 'required|string|in:buyer'
        ]);

        // Generate
        $credential = [
            'username' => 'user_' . $input['global_id'],
            'password' => 'user_' . $input['global_id'] . '_userpw'
        ];

        if (!Auth::attempt($credential)) {
            // Create
            $user = User::Create([
                'phone' => $input['phone'],
                'username' => $credential['username'],
                'password' => $credential['password'],
                'role' => 'buyer',
                'status' => 'limited',
                'last_login' => now()
            ]);
        } else {
            $user = Auth::user();
        }

        // Token
        $payload = [
            "sub" => $user['id'],
            "iat" => now()->timestamp,
            "exp" => now()->timestamp + 1200
        ];

        $token = JWT::encode($payload,env('JWT_SECRET_KEY'),'HS256');

        return response()->json([
            'success' => true,
            'message' => 'success_generate_user',
            'token' => 'Bearer ' . $token
        ], 201);
    }
}
