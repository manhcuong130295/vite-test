<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\ChatInterfaceService;
use App\Transformers\SuccessResource;
use App\Http\Requests\Api\Project\ChatInterfaceRequest;

class ChatInterfaceController extends Controller
{

    /**
     * @var ChatInterfaceService
     */
    private $chatInterfaceService;

    public function __construct(
        ChatInterfaceService $chatInterfaceService
    )
    {
        $this->chatInterfaceService = $chatInterfaceService;
    }


    /**
     * Create new chat interface
     *
     * @param ChatInterfaceRequest $requests
     * @return SuccessResource
     */
    public function create(ChatInterfaceRequest $request): SuccessResource
    {
        $result = $this->chatInterfaceService->updateOrCreate($request);

        return SuccessResource::make($result);
    }

    /**
     * Get chat interface by projectId
     *
     * @param string $project_id
     * @return SuccessResource
     */
    public function indexByProject(string $project_id): SuccessResource
    {
        $result = $this->chatInterfaceService->getByProjectId($project_id);

        return SuccessResource::make($result);
    }
}
