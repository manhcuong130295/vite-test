<?php

namespace App\Repositories;

use App\Models\ChatInterface;
use Illuminate\Database\Eloquent\Collection;

class ChatInterfaceRepository extends BaseRepository
{
    /**
     * ChatInterfaceRepository constructor.
     *
     * @param ChatInterface $model
     */
    public function __construct(ChatInterface $model)
    {
        $this->model = $model;
    }

    /**
     * @function create
     * @description This is function create line channel
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @function updateOrCreate
     * @description This is function create customer
     * @param string project_id
     * @param array $data
     * @return mixed
     */
    public function updateOrCreate($project_id, $data)
    {
        return $this->model->updateOrCreate(['project_id' => $project_id], $data);
    }

    /**
     * get customer by project id
     *
     * @param string $projectId
     */
    public function getByProjectId($projectId)
    {
        return $this->model->where('project_id', $projectId)->first();
    }

    /**
     * get customer by id
     *
     * @param string $id
     */
    public function getById($id)
    {
        return $this->model->where('id', $id)
        ->with('project', function ($query) {
            $query->with('user', function ($query) {
                $query->with('customer', function ($query) {
                    $query->whereHas('subscriptionPlan');
                });
            });
        })
        ->first();
    }

    /**
     * List customer
     *
     * @param array $conditions
     */
    public function list(array $conditions = [])
    {
        #TODO
    }
}
