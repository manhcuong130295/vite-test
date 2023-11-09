<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use App\Services\ZaloChannelService;
use App\Services\ChatService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Exception;

class ReplyQuestionZaloJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ZaloChannelService
     */
    protected $zaloChannelService;

    /**
     * @var ChatService
     */
    protected $chatService;

    /**
     * @var String $channelId
     */
    protected $channelId;

    /**
     * @var Array $data
     */
    protected $data;

    public function __construct(string $channelId, array $data)
    {
        $this->channelId = $channelId;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(ZaloChannelService $zaloChannelService, ChatService $chatService): void
    {
        $data = $this->data;
        $channelId = $this->channelId;
        if (empty($channelId)) {
            return;
        }
        $messageText = "";
        Log::info("Channel_id: " . $channelId);

        if (isset($data['message']) && isset($data['message']['text'])) {
            $question = $data['message']['text'];
            Log::info('Received Zalo message: ' . $question);
            $userZaloId = $data['sender']['id'];
            $this->sendZaloMessage($zaloChannelService, $chatService, $userZaloId, $channelId, $question, $messageText);
        }
    }

    /**
     * send zalo reply message with chatbot ai
     *
     * @param string $replyToken
     * @param string $messageText
     * @param string $channelId
     *
     * @return boolean
     */
    public function sendZaloMessage($zaloChannelService, $chatService, $userZaloId, $channelId, $question, $messageText)
    {
        $zaloChannel = $zaloChannelService->getByUuid($channelId);

        if (!$zaloChannel) {
            Log::error('Channel id does not exist');
            return false;
        }
        $channelToken = $zaloChannel->access_token;
        $clientId = $zaloChannel->client_id;
        $clientSecret = $zaloChannel->client_secret;
        $refreshToken = $zaloChannel->refresh_token;

        $newToken = $this->getNewTokenOA($clientId, $clientSecret, $refreshToken, $channelId, $zaloChannelService);
        if ($newToken) {
            $channelToken = $newToken;
        }

        $project_id = $zaloChannel->project_id;
        //check subscription
        if (!empty($zaloChannel->project) && !empty($zaloChannel->project->user->customer)) {
            if ($zaloChannel->project->user->customer->subscription_plan_id == 1) {
                $messageText = "Sorry! Currently, this feature is temporarily suspended.";
            }
        }

        $history = Cache::get('chat_history_' . $userZaloId, []);

        if (!isset($history[$userZaloId])) {
            $history[$userZaloId] = [];
        }

        if (empty($messageText)) {
            $request = new Request;
            $request->merge(['question' => trim($question)]);
            $request->merge(['project_id' => $project_id]);
            $request->merge(['histories' => array_slice($history[$userZaloId], -6)]);

            $messageText = $chatService->chat($request);
            Log::info($userZaloId);

            array_push($history[$userZaloId], ['role' => 'user', 'content' => $question], ['role' => 'assistant', 'content' => $messageText]);

            $history[$userZaloId] = array_slice($history[$userZaloId], -6);

            Cache::put('chat_history_' . $userZaloId, $history, 1440);
        }
        $apiEndpoint = 'https://openapi.zalo.me/v3.0/oa/message/cs';
        $headers = [
            'Content-Type' => 'application/json',
            'access_token' => $channelToken,
        ];

        $data = [
            'recipient' => [
                'user_id' => $userZaloId,
            ],
            'message' => [
                'text' => $messageText,
            ],
        ];

        $response = Http::withHeaders($headers)
            ->post($apiEndpoint, $data);

        if ($response->successful()) {
            Log::info('REPLY_SUCCESS:' . $messageText);
            return true;
        } else {
            Log::error('REPLY_FAIL:' . $messageText);
            return false;
        }
    }

    /**
     * get new token official account zalo
     * @param in $oa_id
     * @param string $oa_secret
     * @param string $refreshToken
     */
    private function getNewTokenOA($clientId, $clientSecret, $refreshToken, $channelId, $zaloChannelService) {
        try {
            $tokenEndpoint = 'https://oauth.zaloapp.com/v4/oa/access_token?refresh_token='.$refreshToken.'&app_id='.$clientId.'&grant_type=refresh_token';

            $headers = [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Secret_key' => $clientSecret,
            ];

            $response = Http::withHeaders($headers)
                ->post($tokenEndpoint);

            if ($response->successful()) {
                Log::debug(($response));
                $newAccessToken = $response->json('access_token');
                $newRefreshToken = $response->json('refresh_token');
                Log::info('new_token:'. $newAccessToken);
                if($newAccessToken) {
                    $data = [
                        'access_token' => $newAccessToken,
                        'refresh_token' => $newRefreshToken
                    ];
                    $zaloChannelService->updateTokenByUuid($channelId, $data);
                }
                return $newAccessToken;
            } else {
                Log::info('error get token');
                return null;
            }
        }  catch (Exception $e) {
            Log::error("get new token fail");
            return null;
        }
    }
}
