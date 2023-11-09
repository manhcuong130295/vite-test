<?php

namespace App\Services;

use App\Constants\OpenAi;
use App\Jobs\CountMessageJob;
use App\Repositories\ProjectRepository;
use App\Services\OpenAiServices\OpenAiService;
use App\Services\OpenAiServices\PineconeService;
use Exception;
use OpenAI\Responses\StreamResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatService
{
    /**
     * @var OpenAiService
     */
    protected OpenAiService $openAiService;

    /**
     * @var PineconeService
     */
    protected PineconeService $pinconeService;

    /**
     * @var ProjectRepository
     */
    protected ProjectRepository $projectRepository;

    /**
     * Chat service constructor.
     */
    public function __construct(OpenAiService $openAiService, PineconeService $pinconeService, ProjectRepository $projectRepository)
    {
        $this->openAiService = $openAiService;
        $this->pinconeService = $pinconeService;
        $this->projectRepository = $projectRepository;
    }

    /**
     * Chat base from project data
     *
     * @param Request $request
     * @return string $answer
     */
    public function chat(Request $request): string
    {
        try {
            $existProject = $this->projectRepository->checkExistProject($request->get('project_id'));

            if(!$existProject) {
                return OpenAi::PROJECT_NOT_FOUND;
            }

            $embeddingQuestion = $this->openAiService->embedding($request->get('question'));

            $queryResult = $this->pinconeService->query($embeddingQuestion['embedding'], $request->get('project_id'));

            $context = $this->openAiService->buildContext($queryResult);

            $project = $this->projectRepository->findById($request->get('project_id'));

            $user = $project->user()->getQuery()->with(['customer', 'requestStatistics'])->first();

            $prompt = $this->openAiService->buildPrompt($project->name, $context);

            $conversation = $this->openAiService->buildConversation($prompt, $request->get('question'), $project->name, $request->get('histories'));

            $answer = $this->openAiService->chat($conversation);

            Log::info($request->get('question'));

            Log::info($answer);

            if (!empty($answer)) {
                CountMessageJob::dispatch($user)->onQueue('default');
            }

            return $answer;
        } catch (Exception $e) {
            Log::debug('ERROR_WHEN_USING_CHAT: ' . $e);

            return OpenAi::ERROR_MESSAGE;
        }
    }

    /**
     * Chat base from project data
     *
     * @param Request $request
     * @return StreamResponse $answer
     */
    public function chatStream(Request $request): StreamResponse
    {
        $question = urldecode($request->get('question'));

        $embeddingQuestion = $this->openAiService->embedding($question);

        $queryResult = $this->pinconeService->query($embeddingQuestion['embedding'], $request->get('project_id'));

        $context = $this->openAiService->buildContext($queryResult);

        $project = $this->projectRepository->detail($request->get('project_id'));

        $user = $project->user()->getQuery()->with(['customer', 'requestStatistics'])->first();

        $prompt = $this->openAiService->buildPrompt($project->name, $context);

        $conversation = $this->openAiService->buildConversation($prompt, $question, $project->name, $request->get('histories'));

        Log::info($conversation);

        $answer = $this->openAiService->chatStream($conversation);

        Log::info($request->get('question'));

        Log::info(urldecode($request->get('question')));

        if (!empty($answer)) {
            CountMessageJob::dispatch($user)->onQueue('default');
        }

        return $answer;
    }
}
