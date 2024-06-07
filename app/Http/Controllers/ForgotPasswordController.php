<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:users,phone',
        ]);

        $user = User::where('phone', $request->phone)->first();

        $otp = rand(100000, 999999); // Generate OTP
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(1);
        $user->save();

        // Kirim OTP menggunakan gateway WhatsApp
        $this->sendOtpPhone($user->phone, $otp);

        return response()->json(['message' => 'OTP sent successfully']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:users,phone',
            'otp' => 'required',
            'password' => 'required|confirmed',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if ($user->otp !== $request->otp || $user->otp_expires_at->isPast()) {
            return response()->json(['message' => 'Invalid or expired OTP'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json(['message' => 'Password reset successfully']);
    }

    private function sendOtpPhone($phone, $otp)
    {
        $url = env('WHATSAPP_GATEAWAY_URL') . "/api/messages";
        $token = env('WHATSAPP_GATEAWAY_TOKEN');

        $data = [
            "phone" => "$phone",
            "text" => "Kode Otp anda adalah\n*" . $otp . "*",
        ];

        $response = Http::withToken($token)->post($url, $data);

        if ($response->failed()) {
            throw new \Exception('Failed to send OTP');
        }
    }
}
