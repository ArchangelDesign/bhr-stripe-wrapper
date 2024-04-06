<?php

namespace Raffmartinez\BhrStripeWrapper\Core;

class StripeApi
{
    private string $secretKey;

    public function createCheckoutSession(PaymentRequest $request, string $ticketNumber): CheckoutSession
    {
        $client = new \Stripe\StripeClient($this->secretKey);
        $data = [
            'mode' => 'payment',
            'line_items' => [
                'quantity' => $request->getQuantity(),
                'price_data' => [
                    'currency' => 'USD',
                    'product_data' => [
                        'name' => $request->getProductName(),
                    ]
                ]
            ],
            'success_url' => $request->getSuccessUrl(),
            'cancel_url' => $request->getCancelUrl(),
        ];
        $response = $client->checkout->sessions->create($data);

        return new CheckoutSession($response);
    }
}