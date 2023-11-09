<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Project\StoreRequest;
use App\Http\Requests\Api\Project\UpdateRequest;
use App\Services\ProjectService;
use App\Services\UserService;
use App\Transformers\SuccessResource;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * @var ProjectService $projectService
     */
    protected ProjectService $projectService;

     /**
     * @var UserService $userService
     */
    protected UserService $userService;

    /**
     * ProjectController constructor.
     *
     * @param
     */
    public function __construct(ProjectService $projectService, UserService $userService)
    {
        $this->projectService = $projectService;
        $this->userService = $userService;
    }

    /**
     *
     */
    public function index()
    {
        $result = $this->projectService->listProject();

        return SuccessResource::make($result);
    }

    /**
     * List project of current user.
     */
    public function indexByUser(int $userId)
    {
        $user = $this->userService->detail($userId);
        $result = $this->projectService->listProjectsByUserUId($user->uuid);

        return SuccessResource::make($result);
    }

    /**
     * Create new project.
     *
     * @param Request $request
     * @return SuccessResource
     */
    public function createProject(StoreRequest $request): SuccessResource
    {
        $result = $this->projectService->create($request);

        return SuccessResource::make($result);
    }

    /**
     * Update project.
     *
     * @param Request $request
     * @return SuccessResource
     */
    public function updateProject(string $id, UpdateRequest $request): SuccessResource
    {
        $result = $this->projectService->update($id, $request);

        return SuccessResource::make($result);
    }

    /**
     * Delete project.
     * 
     * @param string $id
     * @return SuccessResource
     */
    public function deleteProject(string $id): SuccessResource
    {
        $result = $this->projectService->deleteProject($id);

        return SuccessResource::make($result);
    }
}

