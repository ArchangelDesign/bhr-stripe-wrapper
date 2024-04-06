<?php

namespace Raffmartinez\BhrStripeWrapper\Core;

use Raffmartinez\BhrStripeWrapper\Core\Exceptions\InvalidStripeKey;

class StripeApi
{
    private string $secretKey;

    private string $endpointSecret;

    public function __construct(?string $secretKey = null)
    {
        $key = $secretKey ?? getenv('BHR_STRIPE_CONNECT_SK');
        $this->validateSecretKey($key);
        $this->secretKey = $key;
        $this->endpointSecret = getenv('BHR_STRIPE_WEBHOOK_SECRET');
    }

    public function createCheckoutSession(PaymentRequest $request, ?string $ticketNumber = null): CheckoutSession
    {
        $client = new \Stripe\StripeClient($this->secretKey);
        $data = [
            'mode' => 'payment',
            'line_items' => [
                [
                    'quantity' => $request->getQuantity(),
                    'price_data' => [
                        'currency' => 'USD',
                        'product_data' => [
                            'name' => $request->getProductName(),
                        ],
                        'unit_amount' => $request->getProductPrice(),
                    ]
                ]
            ],
            'success_url' => $request->getSuccessUrl(),
            'cancel_url' => $request->getCancelUrl(),
            'metadata' => [
                'ticketNumber' => $ticketNumber
            ]
        ];
        if (!empty($ticketNumber)) {
            $data['client_reference_id'] = $ticketNumber;
        }
        $response = $client->checkout->sessions->create($data);

        return new CheckoutSession($response);
    }

    public function isCheckoutCompleted(string $data, string $signature): bool
    {
        try {
            $event = \Stripe\Webhook::constructEvent(
                $data, $signature, $this->endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            return false;
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return false;
        }

        switch ($event->type) {
            case 'checkout.session.async_payment_failed':
                $session = $event->data->object;
                return false;
            case 'checkout.session.async_payment_succeeded':
                $session = $event->data->object;
                return true;
            case 'checkout.session.completed':
                $session = $event->data->object;
                return true;
            case 'checkout.session.expired':
                $session = $event->data->object;
                return false;
            default:
                return false;
        }
    }

    public function extractMetadata(string $stripeEvent): array
    {
        $data = json_decode($stripeEvent, true);

        return $data['data']['object']['metadata'];
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