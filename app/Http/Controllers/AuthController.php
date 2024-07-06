<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Services\MessageService;

class AuthController extends Controller
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "username" => "required|string|max:255|unique:users",
            "password" => "required|string|min:8",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "fail_validation",
                "data" => $validator->errors()
            ], 422);
        }

        // User Input
        $user = [
            "username" => $request->get('username'),
            "password" => $request->get('password'),
            "email" => validatorEmail($request->get('email')),
            "phone" => validatorPhone($request->get('phone'))
        ];

        // Otp Email or Phone
        $user['otp_verification'] = generateRandomString(6);

        if ($user['phone']) {
            $this->messageService->sendOtpPhone($user['phone'], $user['otp_verification']);
        } elseif ($user['email']) {
            // $this->sendOtpEmail($user['email'], $user['otp_verification']);
        } else {
            return response()->json([
                "success" => false,
                "message" => "fail_email_phone"
            ]);
        }

        // Input DB
        $resuser = User::create($user);

        $payload = [
            "sub" => $resuser['id'],
            "iat" => now()->timestamp,
            "exp" => now()->timestamp + 7200
        ];

        $token = JWT::encode($payload,env('JWT_SECRET_KEY'),'HS256');

        return response()->json([
            "success" => true,
            "message" => "success_register",
            "token" => "Bearer {$token}"
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                "success" => false,
                "message" => "fail_login"
            ], 401);
        }

        $resuser = Auth::user();

        $payload = [
            "sub" => $resuser['id'],
            "iat" => now()->timestamp,
            "exp" => now()->timestamp + 7200
        ];

        $token = JWT::encode($payload,env('JWT_SECRET_KEY'),'HS256');

        return response()->json([
            "success" => true,
            "message" => "success_login",
            "token" => "Bearer {$token}"
        ]);
    }

    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60
        ]);
    }

}




