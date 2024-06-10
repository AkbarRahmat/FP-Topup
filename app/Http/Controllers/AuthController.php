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

class AuthController extends Controller
{

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
            $this->sendOtpPhone($user['phone'], $user['otp_verification']);
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
            "exp" => now()->timestamp + 1200
        ];

        $token = JWT::encode($payload,env('JWT_SECRET_KEY'),'HS256');

        // Log::create([
        //     'module' => 'login',
        //     'action' => 'login akun',
        //     'useraccess' => $user['email'],
        // ]);

        return response()->json([
            "success" => true,
            "message" => "success_register",
            "token" => "Bearer {$token}"
        ], 200);
    }

    private function sendOtpPhone($phone, $otp)
    {
        $url = env('WHATSAPP_GATEAWAY_URL') . "/api/messages";
        $token = env('WHATSAPP_GATEAWAY_TOKEN');

        $data = [
            "phone" => "$phone",
            "text" => "Kode Otp anda adalah\n*" . $otp . "*",
        ];


        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => $token
        ])->post($url, $data);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                "success" => false,
                "message" => "Invalid credentials",
                "error" => "Unauthorized"
            ], 401);
        }

        return response()->json([
            "success" => true,
            "message" => "Login successful",
            "token" => $token,
            "token_type" => "bearer",
            "expires_in" => config('jwt.ttl') * 60
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



