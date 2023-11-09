<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ProjectService;
use App\Services\LineChannelService;
use App\Services\ZaloChannelService;
use App\Services\ChatInterfaceService;
use App\Services\ProjectContentService;
use App\Services\FacebookFanpageService;
use App\Services\UserService;
use Illuminate\View\View;

use function PHPUnit\Framework\isEmpty;

class ProjectController extends Controller
{
    /**
     * @var ProjectService $projectService
     */
    protected ProjectService $projectService;

    /**
     * @var LineChannelService $lineChannelService
     */
    protected LineChannelService $lineChannelService;

    /**
     * @var ZaloChannelService $zaloChannelService
     */
    protected ZaloChannelService $zaloChannelService;

    /**
     * @var ChatInterfaceService $chatInterfaceService
     */
    protected ChatInterfaceService $chatInterfaceService;

    /**
     * @var UserService $userService
     */
    protected UserService $userService;

    /**
     * @var ProjectContentService $projectContentService
     */
    protected ProjectContentService $projectContentService;

    /**
     * @var FacebookFanpageService
     */
    protected FacebookFanpageService $facebookFanpageService;

    public function __construct(ProjectService $projectService, UserService $userService,
                                    LineChannelService $lineChannelService, ZaloChannelService $zaloChannelService,
                                    ChatInterfaceService $chatInterfaceService, ProjectContentService $projectContentService,
                                    FacebookFanpageService $facebookFanpageService)
    {
        $this->projectService = $projectService;
        $this->userService = $userService;
        $this->lineChannelService = $lineChannelService;
        $this->zaloChannelService = $zaloChannelService;
        $this->chatInterfaceService = $chatInterfaceService;
        $this->projectContentService = $projectContentService;
        $this->facebookFanpageService = $facebookFanpageService;
    }

    /**
     * View list project.
     *
     * @return view
     */
    public function index(): View
    {
        $projects = $this->projectService->listProjectsByUserUId(auth()->user()->uuid);

        $user = $this->userService->detail(auth()->user()->id);

        $subscriptionPlan = $user->customer ? $user->customer->subscriptionPlan : null;

        return view('project.index', compact('projects', 'subscriptionPlan'));
    }

    /**
     * Create new project.
     *
     * @return view
     */
    public function create(): View
    {

        $projects = $this->projectService->listProjectsByUserUId(auth()->user()->uuid);

        $user = $this->userService->detail(auth()->user()->id);

        $subscriptionPlan = $user->customer ? $user->customer->subscriptionPlan : null;

        return view('project.create', compact('projects', 'subscriptionPlan'));
    }

    /**
     * Update project
     *
     * @param string $id
     * @return view
     */
    public function update(string $id): View
    {
        $project = $this->projectService->detail($id);

        $files = $this->projectContentService->getAllFileByProject($id);

        $textContents = $this->projectContentService->getAllTextInputByProject($id);

        $linkContents = $this->projectContentService->getAllLinkContentsByProject($id);

        $lineIntegrate = $this->lineChannelService->getByProjectId($project->id);

        $facebookFanpage = $this->facebookFanpageService->getByProjectId($project->id);

        $zaloIntegrate = $this->zaloChannelService->getByProjectId($project->id);

        $chatInterface = $this->chatInterfaceService->getByProjectId($project->id);

        $user = $this->userService->detail(auth()->user()->id);

        $subscriptionPlan = $user->customer ? $user->customer->subscriptionPlan : null;

        $data = [
            'project' => $project,
            'subscriptionPlan' => $subscriptionPlan,
            'chatInterface' => $chatInterface,
            'files' => $files,
            'textContents' => $textContents,
            'linkContents' => $linkContents,
            'lineIntegrate' => $lineIntegrate,
            'zaloIntegrate' => $zaloIntegrate,
            'facebookFanpage' => $facebookFanpage,
        ];

        return view('project.create', $data);

    }
}

