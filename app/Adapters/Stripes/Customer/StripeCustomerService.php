<?php

namespace App\Adapters\Stripes\Customer;

use App\Adapters\Stripes\StripeAbstract;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;

class StripeCustomerService extends StripeAbstract
{
    private $stripe;

    public function __construct()
    {
        $this->stripe = $this->setApiKey();
    }
}
