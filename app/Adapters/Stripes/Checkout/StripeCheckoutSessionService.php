<?php

namespace App\Adapters\Stripes\Checkout;

use App\Adapters\Stripes\StripeAbstract;
use Exception;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;

class StripeCheckoutSessionService extends StripeAbstract
{
    private $stripe;

    public function __construct()
    {
        $this->stripe = $this->setApiKey();
    }
}
