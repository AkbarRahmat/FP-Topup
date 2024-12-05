<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TripayService
{
    private $baseUrl;
    private $privateKey;
    private $privateToken;
    private $merchantCode;

    public function __construct()
    {
        $this->baseUrl = env('TRIPAY_BASE_URL');
        $this->privateKey = env('TRIPAY_PRIVATE_KEY');
        $this->privateToken = env('TRIPAY_PRIVATE_TOKEN');
        $this->merchantCode = env('TRIPAY_MERCHANT_CODE');
    }

    public function createTransaction($product, $game, $user, $additional)
    {
        $data = [
            'method' => "QRIS2",
            'merchant_ref' => '',
            'amount' => $product->price + $additional['seller_cost'],
            'customer_name' => $user->username,
            'customer_email' => "transaction_kaf@gmail.com",
            'customer_phone' => $user->phone,
            'order_items' => [
                [
                    'sku' => "TOPUP",
                    'name' => $product->name,
                    'price' => $product->price + $additional['seller_cost'],
                    'quantity' => 1,
                ],
            ],
            'callback_url' => "https://domainanda.com/callback",
            'return_url' => "https://domainanda.com/redirect",
            'signature' => ''
        ];

        $data['merchant_ref'] = strtoupper('INV-GAME-' . $game->code . '-' . str_replace(' ', '', $product->name));
        $data['signature'] = hash_hmac('sha256', $this->merchantCode . $data['merchant_ref'] . $data['amount'], $this->privateKey);

        $response = [];
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => $this->privateToken
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
                'Authorization' => $this->privateToken
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
