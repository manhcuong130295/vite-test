<?php

namespace App\Repositories;

use App\Models\ZaloChannels;
use Illuminate\Database\Eloquent\Collection;

class ZaloChannelRepository extends BaseRepository
{
    /**
     * CustomerRepository constructor.
     *
     * @param ZaloChannels $model
     */
    public function __construct(ZaloChannels $model)
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
     * get customer by uuid
     *
     * @param string $projectId
     */
    public function getByProjectId($projectId)
    {
        return $this->model->where('project_id', $projectId)->first();
    }

    /**
     * get customer by uuid
     *
     * @param string $uuid
     */
    public function getById($uuid)
    {
        return $this->model->where('uuid', $uuid)
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
     * update token by uuid
     * @param string uuid
     * @pram array $data
     */
    public function updateTokenByUuid($uuid, $data)
    {
        return $this->model->where('uuid', $uuid)->update($data);
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

    /**
     * Delete customer
     *
     * @param string $uuid
     */
    public function deleteCustomer(string $uuid)
    {
        return $this->model->where('uuid', $uuid)->delete();
    }
}
