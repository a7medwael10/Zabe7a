<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MoyasarService
{
    public function initiatePayment($data)
    {
        $methodMapping = [
            'stcpay' => 'stcpay',
            'mada' => 'creditcard',
            'applepay' => 'applepay',
            'visa' => 'creditcard',
            'banktransfer' => 'banktransfer'
        ];

        $response = Http::withBasicAuth(config('services.moyasar.secret_key'), '')
            ->post('https://api.moyasar.com/v1/payments', [
                'amount' => $data['amount'],
                'currency' => 'SAR',
                'description' => "Order #{$data['order_id']}",
                'callback_url' => config('app.url').'/api/payment/webhook/moyasar',
                'source' => [
                    'type' => $methodMapping[$data['method']],
                    'mobile' => $data['method'] === 'stcpay' ? '9665xxxxxxx' : null
                ]
            ]);

        return [
            'success' => $response->successful(),
            'payment_url' => $response->json('redirect_url'),
            'meta' => $response->json()
        ];
    }
}
