<?php

namespace Raffmartinez\BhrStripeWrapper\Core\Exceptions;

class InvalidStripeKey extends \Exception
{
    public function __construct($message = "")
    {
        parent::__construct('Invalid Stripe key provided: ' . $message);
    }
}