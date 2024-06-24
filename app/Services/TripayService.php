<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TripayService
{
    private $baseUrl;
    private $privateKey;
    private $merchantCode;
    private $merchantRef;
    private $token;

    public function __construct()
    {
        $this->baseUrl = env('TRIPAY_BASE_URL');
        $this->privateKey = env('TRIPAY_PRIVATE_KEY');
        $this->merchantCode = env('TRIPAY_MERCHANT_CODE');
        $this->merchantRef = env('TRIPAY_MERCHANT_REF');
        $this->token = env('TRIPAY_TOKEN');
    }

    public function createTransaction($product, $user)
    {
        $response = [];
        $data = [
            'method' => "QRIS2",
            'merchant_ref' => $this->merchantRef,
            'amount' => $product->price,
            'customer_name' => $user->username,
            'customer_email' => "transactionkaf@gmail.com",
            'customer_phone' => $user->phone,
            'order_items' => [
                [
                    'sku' => "TOPUP",
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 1,
                ],
            ],
            'callback_url' => "https://domainanda.com/callback",
            'return_url' => "https://domainanda.com/redirect",
            'expired_time' => (time() + (24 * 60 * 60)), // Set expired time (optional)
            'signature' => ''
        ];

        $signatureData = $this->merchantCode . $this->merchantRef . $data['amount'];
        $data['signature'] = hash_hmac('sha256', $signatureData, $this->privateKey);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->token
            ])->post("{$this->baseUrl}/transaction/create", $data)->json();
        } catch (\Throwable $e) {
            $response = [
                'success' => false,
                'debug' => $e->getMessage()
            ];
        }

        return $response;
    }

    public function getTransaction($transactionId)
    {
        $response = [];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->token
            ])->get("{$this->baseUrl}/transaction/detail?transaction_id=$transactionId")->json();
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'debug' => $e->getMessage()
            ];
        }

        return $response;
    }
}
