<?php

namespace App\Adapters\Stripes\Subscription;

use App\Adapters\Stripes\StripeAbstract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\ApiErrorException;

class StripeSubscriptionService extends StripeAbstract
{
    private $stripe;

    public function __construct()
    {
        $this->stripe = $this->setApiKey();
    }

}
