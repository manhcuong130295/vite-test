<?php

namespace App\Http\Controllers\Api;

use App\Constants\OpenAi;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Chat\QuestionRequest;
use App\Services\ChatService;
use App\Transformers\SuccessResource;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
class ChatController extends Controller
{
    /**
     * @var ChatService
     */
    protected ChatService $chatService;

    /**
     * Chat controller constructor
     *
     * @param ChatService $chatService
     */
    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Chat base from project content
     *
     * @param QuestionRequest $request
     * @return SuccessResource
     */
    public function chat(QuestionRequest $request): SuccessResource
    {
        $result = $this->chatService->chat($request);

        return SuccessResource::make(['answer' => $result]);
    }

    /**
     * Chat base from project content response by stream event.
     *
     * @param QuestionRequest $request
     * @return StreamedResponse
     */
    public function chatStream(QuestionRequest $request): StreamedResponse
    {
        return response()->stream(function () use ($request) {
            try {
                $stream = $this->chatService->chatStream($request);

                foreach ($stream as $response) {
                    $text = $response->choices[0]->delta->content;
                    Log::info('Text_stream:' . $text);

                    if (connection_aborted()) {
                        break;
                    }
                    echo $text;
                    flush();
                }
            } catch (Exception $e) {
                Log::debug($e);

                foreach(explode(' ', OpenAi::ERROR_MESSAGE) as $text) {
                    echo "$text ";
                    flush();
                }
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Content-Type' => 'text/event-stream',
        ]);
    }
}
