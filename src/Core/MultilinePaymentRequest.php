<?php

namespace Raffmartinez\BhrStripeWrapper\Core;

use Raffmartinez\BhrStripeWrapper\Core\Exceptions\InvalidPaymentRequest;

class MultilinePaymentRequest
{
    private string $successUrl;

    private string $cancelUrl;

    private array $productsAndPrices;

    public function __construct(string $successUrl, string $cancelUrl, array $productsAndPrices)
    {
        $this->successUrl = $successUrl;
        $this->cancelUrl = $cancelUrl;
        $this->productsAndPrices = $productsAndPrices;

        if (empty($productsAndPrices)) {
            throw new InvalidPaymentRequest('No products provided');
        }
        foreach ($productsAndPrices as $entry) {
            if (!isset($entry['name']) || !isset($entry['price']) || !isset($entry['requestId'])) {
                throw new InvalidPaymentRequest('Missing product name or price.');
            }
            if ($entry['price'] < 100) {
                throw new InvalidPaymentRequest('Price on the product ' . $entry['name'] . 'is too low.');
            }
        }
    }

    /**
     * @return string
     */
    public function getSuccessUrl(): string
    {
        return $this->successUrl;
    }

    /**
     * @return string
     */
    public function getCancelUrl(): string
    {
        return $this->cancelUrl;
    }

    /**
     * @return array
     */
    public function getProductsAndPrices(): array
    {
        return $this->productsAndPrices;
    }
}