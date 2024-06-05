<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Log;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return messageError($validator->messages()->toArray());
        }

        $user = $validator->validated();

        User::create($user);
        
        $payload = [
            'username' => $user['username'],
            'role' => 'user',
            'iat' => now()->timestamp,
            'exp' => now()->timestamp + 1200
        ];

        $token = JWT::encode($payload,env('JWT_SECRET_KEY'),'HS256');

        Log::create([
            'module' => 'login',
            'action' => 'login akun',
            'useraccess' => $user['email'],
        ]);
        return response()->json([
            "data" => [
                'msg' => "Selamat Datang!",
                'username' => $user['username'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'role' => 'user',
            ],
            "token" => "Bearer {$token}"
        ], 200);
    }

  

}
