<?php

namespace Raffmartinez\BhrStripeWrapper\Core;

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
     */
    public function __construct(string $successUrl, string $cancelUrl, string $productName, int $productPrice, int $quantity)
    {
        $this->successUrl = $successUrl;
        $this->cancelUrl = $cancelUrl;
        $this->productName = $productName;
        $this->productPrice = $productPrice;
        $this->quantity = $quantity;
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