<?php

namespace Raffmartinez\BhrStripeWrapper\Core;

class WebhookProcessor
{
    /**
     * Returns metadata from the checkout webhook when request has been validated
     * Returns null when payment has been declined or request is not authentic
     *
     * @param array $data
     * @param string $signature
     * @return array|null
     */
    public static function processWebhookEvent(array $data, string $signature): ?array
    {
        $api = new StripeApi();
        $data = file_get_contents('php://input');
        $signature = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        if (!$api->isCheckoutCompleted($data, $signature)) {
            return null;
        }

        return $api->extractMetadata($data);
    }
}