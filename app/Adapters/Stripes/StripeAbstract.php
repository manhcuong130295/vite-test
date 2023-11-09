<?php

namespace App\Adapters\Stripes;

use Stripe\StripeClient;

abstract class StripeAbstract
{
    /**
     * Set ApiKey.
     *
     * @return StripeClient
     */
    protected function setApiKey(): StripeClient
    {
        return new StripeClient(env('STRIPE_SECRET'));
    }
}
