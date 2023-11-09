<?php

namespace App\Adapters\Messengers;

use App\Adapters\Messengers\MessengerAbstract;
use App\Jobs\ReplyFacebookMessengerJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Services\FacebookFanpageService;
use App\Services\MessengerService;
use Illuminate\Http\Response;

class MessengerWebhookService extends MessengerAbstract
{
    /**
     * @var FacebookFanpageService
     */
    protected $facebookFanpageService;

    /**
     * @var MessengerService
     */
    protected $messengerService;

    public function __construct(FacebookFanpageService $facebookFanpageService, MessengerService $messengerService)
    {
        $this->facebookFanpageService = $facebookFanpageService;
        $this->messengerService = $messengerService;
    }

    public function verify(Request $request)
    {
        $hubVerifyToken = $request->input('hub_verify_token');// Use the verify token you set in the Facebook App
        $check = $this->facebookFanpageService->checkExists($hubVerifyToken);
        $challenge = $request->input('hub_challenge');
        $mode = $request->input('hub_mode');
    
        if ($mode === 'subscribe' && $check) {
            return $challenge;
        }
    
        return false;
    }

    public function handleWebhook(string $id, Request $request)
    {
        Log::info($request->toArray());

        $entries = $request->get('entry');

        ReplyFacebookMessengerJob::dispatch($id, $entries)->onQueue('default');

        return response(Response::HTTP_OK);
    }
}
