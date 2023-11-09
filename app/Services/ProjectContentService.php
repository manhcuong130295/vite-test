<?php

namespace App\Services;

use App\Repositories\ProjectContentRepository;
use Illuminate\Database\Eloquent\Collection;

class ProjectContentService
{

    /**
     * @var ProjectcontentRepository
     */
    protected $projectContentRepository;

    public function __construct(ProjectContentRepository $projectContentRepository)
    {
        $this->projectContentRepository = $projectContentRepository;
    }

    /**
     * Get all files contents by project id.
     * 
     * @param string $projectId
     * @return Collection
     */
    public function getAllFileByProject(string $projectId): Collection
    {
        $result = $this->projectContentRepository->getAllFileByProjectId($projectId);

        return $result;
    }

    /**
     * Get all texts contents by project id.
     * 
     * @param string $projectId
     * @return Collection
     */
    public function getAllTextInputByProject(string $projectId): Collection
    {
        $result = $this->projectContentRepository->getAllTextContentsByProjectId($projectId);

        return $result;
    }

        /**
     * Get all texts contents by project id.
     * 
     * @param string $projectId
     * @return Collection
     */
    public function getAllLinkContentsByProject(string $projectId): Collection
    {
        $result = $this->projectContentRepository->getAllLinkContentsByProjectId($projectId);

        return $result;
    }
}
