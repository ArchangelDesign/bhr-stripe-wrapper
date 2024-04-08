<?php

namespace Raffmartinez\BhrStripeWrapper;

use Raffmartinez\BhrStripeWrapper\Core\CheckoutSession;
use Raffmartinez\BhrStripeWrapper\Core\PaymentRequest;
use Raffmartinez\BhrStripeWrapper\Core\StripeApi;

class Checkout
{
    private static string $defaultSuccessUrl = 'https://blackhorserepairs.com/payment/success';

    private static string $defaultCancelUrl = "https://blackhorserepairs.com/payment/cancel";

    /**
     * Creates checkout session and returns URL to redirect to it
     *
     * @param string $productName
     * @param int $price
     * @param string|null $ticketNumber
     * @return string
     * @throws Core\Exceptions\InvalidPaymentRequest
     */
    public static function simpleCheckout(string $productName, int $price, ?string $ticketNumber = null): string
    {
        $api = new StripeApi();
        $request = new PaymentRequest(self::$defaultSuccessUrl, self::$defaultCancelUrl, $productName, $price, 1);
        $session = $api->createCheckoutSession($request, $ticketNumber);
        return $session->getUrl();
    }

    public static function checkout(string $productName, int $price, string $ticketNumber, int $paymentRecordId, array $extra = []): CheckoutSession
    {
        $api = new StripeApi();
        $request = new PaymentRequest(self::$defaultSuccessUrl, self::$defaultCancelUrl, $productName, $price, 1);
        $meta = [
            'ticketNumber' => $ticketNumber,
            'paymentRecordId' => $paymentRecordId
        ];
        $meta = array_merge($meta, $extra);
        return $api->createCheckoutSessionWithMeta($request, $meta);
    }

    public static function setSuccessUrl(string $url)
    {
        self::$defaultSuccessUrl = $url;
    }

    public static function setCancelUrl(string $url)
    {
        self::$defaultCancelUrl = $url;
    }
}