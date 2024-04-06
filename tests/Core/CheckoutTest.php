<?php

namespace Test\Core;

use PHPUnit\Framework\TestCase;
use Raffmartinez\BhrStripeWrapper\Checkout;

class CheckoutTest extends TestCase
{
    public function testCreateSimpleCheckoutSession()
    {
        $url = Checkout::simpleCheckout('ECM Repair', 10000, 'XXX-XXQAS-12345');

        $this->assertStringContainsString('https://', $url);
    }
}