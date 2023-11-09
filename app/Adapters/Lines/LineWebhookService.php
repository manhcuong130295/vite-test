<?php

namespace App\Adapters\Lines;

use App\Adapters\Lines\LineAbstract;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\LineChannelService;
use App\Services\CustomerService;
use App\Services\ProjectService;
use App\Transformers\SuccessResource;
use App\Transformers\ErrorResource;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\Facades\Http;
use App\Services\ChatService;
use Illuminate\Support\Facades\Cache;

class LineWebhookService extends LineAbstract
{
    /**
     * @var LineChannelService
     */
    protected $lineChannelService;

    /**
     * @var ChatService
     */
    protected $chatService;

    /**
     * @var CustomerService
     */
    protected $customerService;

    /**
     * @var ProjectService
     */
    protected $projectService;

    /**
     * @param LineChannelService $customerService
     */
    public function __construct(LineChannelService $lineChannelService, ChatService $chatService, CustomerService $customerService, ProjectService $projectService)
    {
        $this->lineChannelService = $lineChannelService;
        $this->chatService = $chatService;
        $this->customerService = $customerService;
        $this->projectService = $projectService;
    }

    /**
     * handle webhook line channel callback
     *
     * @param Request $request
     */
    public function webhook(Request $request)
    {
        $messageText = "";
        $question = "";
        $body = $request->all();
        $channelId = $request->input('id');
        if (empty($channelId)) {
            return new ErrorResource('422');
        }
        Log::info("Channel_id: " . $channelId);

        foreach ($body['events'] as $event) {
            if ($event['message']['type'] != 'text') {
                Log::warning('Type is not a text type');
                $messageText = trans('message.line.question_not_reply');
            } else {
                $question = $event['message']['text'];
                Log::info('Question: ' . $question);
            }

            $replyToken = $event['replyToken'];
            $userLineId = $event['source']['userId'];

            $this->sendLineReply($replyToken, $question, $channelId, $messageText, $userLineId);
        }

        return new SuccessResource();
    }

    /**
     * send line reply message with chatbot ai
     *
     * @param string $replyToken
     * @param string $messageText
     * @param string $channelId
     *
     * @return boolean
     */
    private function sendLineReply($replyToken, $question, $channelId, $messageText, $userLineId)
    {
        $lineChannel = $this->lineChannelService->getByUuid($channelId);

        if (!$lineChannel) {
            Log::error('Channel id does not exist');
            return false;
        }
        Log::info($lineChannel->project->user->customer);

        $history = Cache::get('chat_history_' . $userLineId, []);

        if (!isset($history[$userLineId])) {
            $history[$userLineId] = [];
        }

        $channelToken = $lineChannel->access_token;
        $project_id = $lineChannel->project_id;

        //check subscription
        if (!empty($lineChannel->project) && !empty($lineChannel->project->user->customer)) {
            if ($lineChannel->project->user->customer->subscription_plan_id == 1) {
                $messageText = "Sorry! Currently, this feature is temporarily suspended.";
            }
        }
        
        if (empty($messageText)) {
            $request = new Request;
            $request->merge(['question' => trim($question)]);
            $request->merge(['project_id' => $project_id]);
            $request->merge(['histories' => array_slice($history[$userLineId], -6)]);

            $messageText = $this->chatService->chat($request);
            Log::info($messageText);
            array_push($history[$userLineId], ['role' => 'user', 'content' => $question], ['role' => 'assistant', 'content' => $messageText]);

            $history[$userLineId] = array_slice($history[$userLineId], -6);

            Cache::put('chat_history_' . $userLineId, $history, 1440);
        }

        $url = 'https://api.line.me/v2/bot/message/reply';
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $channelToken,
        ];

        $data = [
            'replyToken' => $replyToken,
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $messageText,
                ],
            ],
        ];

        $response = Http::withHeaders($headers)
            ->post($url, $data);

        if ($response->successful()) {
            Log::info('REPLY_SUCCESS:' . $messageText);
            return true;
        } else {
            Log::error('REPLY_FAIL:' . $messageText);
            return false;
        }
    }
}
