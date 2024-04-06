<?php

namespace Raffmartinez\BhrStripeWrapper\Core;

use Raffmartinez\BhrStripeWrapper\Core\Exceptions\InvalidStripeKey;

class StripeApi
{
    private string $secretKey;

    public function __construct(?string $secretKey = null)
    {
        $key = $secretKey ?? getenv('BHR_STRIPE_CONNECT_SK');
        $this->validateSecretKey($key);
        $this->secretKey = $key;
    }

    public function createCheckoutSession(PaymentRequest $request, ?string $ticketNumber = null): CheckoutSession
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
        if (!empty($ticketNumber)) {
            $data['client_reference_id'] = $ticketNumber;
        }
        $response = $client->checkout->sessions->create($data);

        return new CheckoutSession($response);
    }

    /**
     * @throws InvalidStripeKey
     */
    private function validateSecretKey(string $key)
    {
        if (empty($key)) {
            throw new InvalidStripeKey('The key cannot be empty');
        }

        if (strpos($key, ' ') !== false) {
            throw new InvalidStripeKey('The key cannot contain spaces');
        }

        if (strlen($key) < 15) {
            throw new InvalidStripeKey('The key is too short.');
        }

        if (strpos($key, 'sk_') !== 0) {
            throw new InvalidStripeKey('The key does not appear to be valid. It should start with "sk_"');
        }
    }
}