<?php

namespace Raffmartinez\BhrStripeWrapper\Core;

use Raffmartinez\BhrStripeWrapper\Core\Exceptions\InvalidPaymentRequest;

class PaymentRequest
{
    private string $successUrl;

    private string $cancelUrl;

    private string $productName;

    private int $productPrice;

    private int $quantity;

    /**
     * @param string $successUrl
     * @param string $cancelUrl
     * @param string $productName
     * @param int $productPrice
     * @param int $quantity
     * @throws InvalidPaymentRequest
     */
    public function __construct(string $successUrl, string $cancelUrl, string $productName, int $productPrice, int $quantity)
    {
        $this->successUrl = $successUrl;
        $this->cancelUrl = $cancelUrl;
        $this->productName = $productName;
        $this->productPrice = $productPrice;
        $this->quantity = $quantity;

        if ($productPrice < 100) {
            throw new InvalidPaymentRequest('The amount is too low, must be at least $1.00 (100).');
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
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @return int
     */
    public function getProductPrice(): int
    {
        return $this->productPrice;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }
}