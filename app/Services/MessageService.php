<?php
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MessageService
{
    public function sendOtpPhone($phone, $otp)
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
}
