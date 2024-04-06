<?php

namespace Raffmartinez\BhrStripeWrapper\Core;

use Stripe\Checkout\Session;

class CheckoutSession
{
    private Session $session;

    public function __construct(Session $sessionObject)
    {
        $this->session = $sessionObject;
    }

    /**
     * @return Session
     */
    public function getSessionObject(): Session
    {
        return $this->session;
    }

    public function getUrl(): string
    {
        return $this->session->url;
    }

    public function isValid(): bool
    {

    }
}