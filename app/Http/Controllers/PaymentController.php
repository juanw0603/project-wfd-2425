<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MidtransService;

class PaymentController extends Controller
{
    public function createPayment(Request $request, MidtransService $midtrans)
    {
        $payload = [
            'payment_type' => 'bank_transfer',
            'transaction_details' => [
                'order_id' => 'ORDER-' . time(),
                'gross_amount' => 100000,
            ],
            'bank_transfer' => [
                'bank' => 'bca',
            ],
            'customer_details' => [
                'first_name' => 'Juanito',
                'email' => 'juanito@example.com',
            ],
        ];

        $result = $midtrans->createTransaction($payload);

        return response()->json($result);
    }

    public function handleCallback(Request $request) {}

    public function showStatus($orderId) {}
}
