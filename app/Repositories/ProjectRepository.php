<?php

namespace App\Repositories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository
{
    /**
     * List projetc of current user.
     *
     * @param string $userId
     * @return Collection
     */
    public function listAllProjectsByUserUId(string $userUuid): Collection
    {
        return Project::query()
                ->where('user_uuid', $userUuid)
                ->with('user', function ($query) {
                    $query->with('customer', function ($query) {
                        $query->whereHas('subscriptionPlan');
                    });
                })
                ->get();
    }

    /**
     * List all projects
     * 
     * @return Collection
     */
    public function list(): Collection
    {
        return Project::query()
            ->with('user', function ($query) {
                $query->with('customer', function ($query) {
                    $query->with('subscriptionPlan');
                });
            })
            ->get();
    }

    /**
     * List all project not processed yet.
     * 
     * @return Collection
     */
    public function listUnprocess(): Collection
    {
        return Project::query()->where('processing_status', 0)->get();
    }

    public function detail(string $id): ?Project
    {
        return Project::query()->findOrFail($id);
    }

    public function checkExistProject(string $id): bool
    {
        return Project::query()->where('id', $id)->exists();
    }

    public function findById(string $id): ?Project
    {
        return Project::query()->where('id', $id)->first();
    }
}

