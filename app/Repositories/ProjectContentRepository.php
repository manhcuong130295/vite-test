<?php

namespace App\Repositories;

use App\Constants\TrainingType;
use App\Models\ProjectContent;
use Illuminate\Database\Eloquent\Collection;

class ProjectContentRepository extends BaseRepository
{
    /**
     * ProjectFileRepository constructor.
     *
     * @param ProjectContent $model
     */
    public function __construct(ProjectContent $model)
    {
        $this->model = $model;
    }

    /**
     * Get all files by Project id.
     * 
     * @param string $projectId
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getAllFileByProjectId(string $projectId): Collection
    {
        return $this->model->where('project_id', $projectId)
                            ->where('type', TrainingType::FILE)
                            ->get();
    }

    /**
     * Get all files by Project id.
     * 
     * @param string $projectId
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getAllTextContentsByProjectId(string $projectId): Collection
    {
        return $this->model->where('project_id', $projectId)
                            ->where('type', TrainingType::TEXT)
                            ->get();
    }

    /**
     * Get all files by Project id.
     * 
     * @param string $projectId
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getAllLinkContentsByProjectId(string $projectId): Collection
    {
        return $this->model->where('project_id', $projectId)
                            ->where('type', TrainingType::LINK)
                            ->get();
    }

    /**
     * Get all files by Project id.
     * 
     * @param string $projectId
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getAllContentsByProjectId(string $projectId): Collection
    {
        return $this->model->where('project_id', $projectId)
                            ->get();
    }
}