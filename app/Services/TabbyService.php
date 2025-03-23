<?php

// app/Services/TabbyService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class TabbyService
{
    public function createInstallmentSession($data)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.config('services.tabby.secret_key'),
            'Content-Type' => 'application/json'
        ])->post('https://api.tabby.dev/api/v2/checkout', [
            'payment' => [
                'amount' => $data['amount'],
                'currency' => 'SAR',
                'buyer' => [
                    'phone' => $data['user']->phone,
                    'email' => $data['user']->email
                ],
                'order' => [
                    'reference_id' => $data['order_id'],
                    'items' => [[
                        'title' => 'Order #'.$data['order_id'],
                        'quantity' => 1,
                        'unit_price' => $data['amount']
                    ]]
                ]
            ]
        ]);

        return [
            'success' => $response->successful(),
            'installment_url' => $response->json('configuration.available_products.installments.0.web_url'),
            'meta' => $response->json()
        ];
    }
}
