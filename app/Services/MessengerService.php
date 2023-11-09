<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Services\ChatService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MessengerService
{    
    /**
    * @var Client
    */
    protected $client;

    /**
     * @var ChatService
     */
    protected $chatService;

    /**
     * @var FacebookFanpage
     */
    protected $facebookFanpageService;

    public function __construct(Client $client, ChatService $chatService, FacebookFanpageService $facebookFanpageService)
    {
        $this->client = $client;
        $this->chatService = $chatService;
        $this->facebookFanpageService = $facebookFanpageService;
    }

    public function handleMessengerRequest(string $id, array $entries)
    {
        $fanpage = $this->facebookFanpageService->findById($id);
        
        $pageId = $entries[0]['id'];

        if ($fanpage->page_id !== $pageId) {
            return response(Response::HTTP_BAD_REQUEST);
        }
        
        $repicientId = $entries[0]['messaging'][0]['sender']['id'];
        Log::info('repicientId:' . $repicientId);

        if(!empty($entries[0]['messaging'][0]['message']) && isset($entries[0]['messaging'][0]['message']['text'])) {
            $messageText = $entries[0]['messaging'][0]['message']['text'];
            Log::info('text:' . $messageText);
            $this->sendReplyMessage($pageId, $repicientId, $messageText, $fanpage);
        }
    }

    public function sendReplyMessage($pageId, $repicientId, $messegeText, $fanpage)
    {
        $url = 'https://graph.facebook.com/v17.0/' . $pageId .'/messages?access_token=' . $fanpage->access_token;

        $projectId = $fanpage->project_id;

        $histories = Cache::get('chat_histories_' . $repicientId, []);

        if (!isset($histories[$repicientId])) {
            $histories[$repicientId] = [];
        }

        Log::info($histories[$repicientId]);
        
        $request = new Request([
            'question' => $messegeText,
            'project_id' => $projectId,
            'histories' => array_slice($histories[$repicientId], -6)
        ]);

        $answer = $this->chatService->chat($request);

        array_push($histories[$repicientId], ['role' => 'user', 'content' => $messegeText], ['role' => 'assistant', 'content' => $answer]);

        $histories[$repicientId] = array_slice($histories[$repicientId], -6);

        Cache::put('chat_histories_' . $repicientId, $histories, 1440);

        $params = [
            'recipient' => ['id' => $repicientId],
            'message' => ['text' => $answer]
        ];

        $response = $this->client->post($url, ['json' => $params]);

        $response = json_decode($response->getBody()->getContents(), true);
    }
}
