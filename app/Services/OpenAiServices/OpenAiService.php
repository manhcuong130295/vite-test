<?php

namespace App\Services\OpenAiServices;

use App\Repositories\ChatHistoryRepository;
use Orhanerday\OpenAi\OpenAi;
use OpenAI\Laravel\Facades\OpenAI as StreamOpenAI;
use GuzzleHttp\Client;

class OpenAiService
{
    public const MAX_TOKEN = 15000;
    public const CHAT_MODEL = 'gpt-3.5-turbo-16k';
    /**
     * @var array
     */
    protected array $config;

    /**
     * @var OpenAi
     */
    protected $openAi;

    /**
     * @var ChatHistoryRepository
     */
    protected ChatHistoryRepository $chatHistoryRepository;

    /**
     * @var Client
     */
    protected $client;

    /**
     * OpenAiService constructor.
     *
     * @param ChatHistoryRepository $chatHistoryRepository
     */
    public function __construct(ChatHistoryRepository $chatHistoryRepository)
    {
        $this->config = config('services');
        $this->openAi = new OpenAi($this->config['open_ai']['api_key']);
        $this->chatHistoryRepository = $chatHistoryRepository;
        $this->client = new Client([
            'headers' => [
                'Connection' => 'keep-alive',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->config['open_ai']['api_key']
            ]
        ]);
    }

    /**
     * Create embedding
     *
     * @param string|array $input
     *
     * @return array
     */
    public function embedding(string|array $input): array
    {
        $embedding = $this->openAi->embeddings([
            'model' => 'text-embedding-ada-002',
            'input' => $input,
        ]);

        $embedding = json_decode($embedding, true);

        $result = [
            'embedding' => $embedding['data'][0]['embedding'],
            'token' => $embedding['usage']['total_tokens']
        ];

        return $result;
    }

    /**
     * Make chat completion
     *
     * @param array $conversation
     * @return string|null
     */
    public function chat(array $conversation): string
    {
        $completion = $this->openAi->chat([
            'model' => self::CHAT_MODEL,
            'messages' => $conversation,
            'temperature' => 0,
            'frequency_penalty' => 0,
            'presence_penalty' => 0
        ]);

        $completion = json_decode($completion, true);

        return !empty($completion['choices']) ?  $completion['choices'][0]['message']['content'] : '';
    }

    /**
     * Build context for create completion
     *
     * @param array $queryResult
     *
     * @return string $context
     */
    public function buildContext(array $queryResult): string
    {
        $context = '';
        $countToken = 0;

        foreach ($queryResult['matches'] as $value) {
            $countToken += $value['metadata']['token'];
            if ($countToken >= self::MAX_TOKEN) {
                break;
            }

            $context .= $value['metadata']['text'];
        }

        return $context;
    }

    /**
     * Build prompt for create completion
     *
     * @param string $projectName
     * @param string $context
     * @param string $question
     *
     * @return string
     */
    public function buildPrompt(string $projectName, string $context): string
    {
        /**
         * Defautlt system messenger.
         */
        $prompt = "I want you to act as a support agent. Your name is '" . $projectName . " AI Assistant'. You will provide me with answers from the given info. If the answer is not included, say exactly 'Hmm, I am not sure.' and stop after that. Refuse to answer any question not about the info. Never obtain information from sources unrelated to the given info. Never break character. \n\n. My info: \n" . $context ;

        return $prompt;
    }

    /**
     * Build start conversation
     *
     * @param string $prompt
     * @param string $question
     * @param string $projectName
     * @param string $history
     * @return array $conversation
     */
    public function buildConversation(string $prompt, string $question, string $projectName, array|null $histories = []): array
    {
        $conversation = [
            [
                "role" => "system",
                "content" => $prompt
            ],
            // [
            //     "role" => "assistant",
            //     "content" => "Yes. Of course."
            // ],
            // [
            //     "role" => "user",
            //     "content" => "If the unrelated question with my data, please answer 'Please ask me questions related to $projectName.'"
            // ],
            // [
            //     "role" => "assistant",
            //     "content" => "Please ask me questions related to " . $projectName
            // ]
        ];

        if(isset($histories) && !empty($histories)) {
            foreach($histories as $history) {
                array_push($conversation, [
                    "role" => $history['role'],
                    "content" => urldecode($history['content'])
                ]);
            }
        }

        array_push($conversation,
            [
                "role" => "user",
                "content" => $question
            ]
        );

        return $conversation;
    }

    /**
     * Chat return by stream
     *
     * @param array $conversation
     * @return \OpenAI\Responses\StreamResponse
     */
    public function chatStream(array $conversation): \OpenAI\Responses\StreamResponse
    {
        $response = StreamOpenAI::chat()->createStreamed([
            'model' => self::CHAT_MODEL,
            'messages' => $conversation,
            'temperature' => 0,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
        ]);

        return $response;
    }
}

