<?php

namespace Raffmartinez\BhrStripeWrapper;

use Raffmartinez\BhrStripeWrapper\Core\CheckoutSession;
use Raffmartinez\BhrStripeWrapper\Core\MultilinePaymentRequest;
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

    /**
     * @param string $productName
     * @param int $price
     * @param string $ticketNumber
     * @param int $paymentRecordId
     * @param array $extra
     * @return CheckoutSession
     * @throws Core\Exceptions\InvalidPaymentRequest
     */
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

    /**
     * @param array $productsAndPrices [ [ 'name' => <product name>, 'price' => <price in cents>, 'requestId' => <id of payment request record ] ]
     * @param int $total
     * @param string $ticketNumber
     * @param int $paymentRecordId
     * @param array $extra
     * @return CheckoutSession
     * @throws Core\Exceptions\InvalidPaymentRequest
     */
    public static function multilineCheckout(array $productsAndPrices, int $total, string $ticketNumber, int $paymentRecordId, array $extra): CheckoutSession
    {
        $request = new MultilinePaymentRequest(self::$defaultSuccessUrl, self::$defaultCancelUrl, $productsAndPrices, $extra);
        $api = new StripeApi();
        $requests = [];
        foreach ($productsAndPrices as $entry) {
            $requests[] = $entry['requestId'];
        }
        $extra = [
            'ticketNumber' => $ticketNumber,
            'paymentRecordId' => $paymentRecordId,
            'requests' => json_encode($requests)
        ];
        $api->createMultilineCheckoutSessionWithMeta($request, $extra);
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