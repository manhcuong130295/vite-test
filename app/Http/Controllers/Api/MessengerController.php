<?php

namespace App\Http\Controllers\Api;

use App\Adapters\Messengers\MessengerWebhookService;
use App\Http\Controllers\Controller;
use App\Transformers\SuccessResource;
use Illuminate\Http\Request;

class MessengerController extends Controller
{
    /**
     * @var MessengerWebhookService
     */
    protected $messengerWebhookService;

    public function __construct(MessengerWebhookService $messengerWebhookService)
    {
        $this->messengerWebhookService = $messengerWebhookService;
    }

    public function verify(Request $request)
    {
        $result = $this->messengerWebhookService->verify($request);

        return $result ? response($result, 200) : response('Invalid Request', 403);
    }

    public function handleWebhook(string $id, Request $request): SuccessResource
    {
        $this->messengerWebhookService->handleWebhook($id, $request);

        return new SuccessResource();
    }
}

