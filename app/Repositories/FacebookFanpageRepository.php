<?php

namespace App\Repositories;

use App\Models\FacebookFanpage;

class FacebookFanpageRepository extends BaseRepository
{
    /**
     * FacebookFanpageRepository constructor.
     *
     * @param FacebookFanpage $model
     */
    public function __construct(FacebookFanpage $model)
    {
        $this->model = $model;
    }

    /**
     * @function create
     * @description This is function create facebook fanpage.
     * 
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
    public function updateOrCreate($project_id, $data): ?FacebookFanpage
    {
        return $this->model->updateOrCreate(['project_id' => $project_id], $data);
    }

    public function findById(string $id): ?FacebookFanpage
    {
        return $this->getById($id);
    }

    public function checkExists(string $id): bool
    {
        return $this->model->query()->where('id', $id)->exists();
    }

    public function getByProjectId(string $id): ?FacebookFanpage
    {
        return $this->model->query()->where('project_id', $id)->first();
    }
}
