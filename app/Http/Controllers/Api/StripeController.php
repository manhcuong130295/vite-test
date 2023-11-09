<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Stripe;
use App\Adapters\Stripes\StripeWebhookService;

class StripeController extends Controller
{
    /**
     * @var StripeWebhookService
     */
    private $stripeWebhookService;

    public function __construct(
        StripeWebhookService $stripeWebhookService
    )
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));
        $this->stripeWebhookService = $stripeWebhookService;
    }

    /**
     * Handle webhook stripe call
     * @param Request $request
     *
     */
    public function handleWebhook(Request $request) {
        return $this->stripeWebhookService->webhook($request);
    }
}
