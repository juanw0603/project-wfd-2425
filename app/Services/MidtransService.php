<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MidtransService
{
    protected $serverKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key');
        $this->baseUrl = config('services.midtrans.is_production')
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';
    }

    public function createTransaction(array $params)
    {
        $response = Http::withBasicAuth($this->serverKey, '')
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post($this->baseUrl . '/v2/charge', $params);

        return $response->json();
    }

    public function checkTransactionStatus($orderId)
    {
        $response = Http::withBasicAuth($this->serverKey, '')
            ->get($this->baseUrl . '/v2/' . $orderId . '/status');

        return $response->json();
    }

    public function cancelTransaction($orderId)
    {
        return Http::withBasicAuth($this->serverKey, '')
            ->post($this->baseUrl . '/v2/' . $orderId . '/cancel')
            ->json();
    }
}
