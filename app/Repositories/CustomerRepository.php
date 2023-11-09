<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository extends BaseRepository
{
    /**
     * CustomerRepository constructor.
     *
     * @param Customer $model
     */
    public function __construct(Customer $model)
    {
        $this->model = $model;
    }

    /**
     * @function create
     * @description This is function create customer
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
     * @param string user_uuid
     * @param array $data
     * @return mixed
     */
    public function updateOrCreate($user_uuid, $data)
    {
        return $this->model->updateOrCreate(['user_uuid' => $user_uuid], $data);
    }

    /**
     * get customer by id
     *
     * @param int $id
     */
    public function getById($id)
    {
        return $this->model->with(['event'])->find($id);
    }

    /**
     * Get by user_uuid
     * @param string user_uuid
     *
     */
    public function getByUser(string $user_uuid)
    {
        return $this->model->where('user_uuid', $user_uuid)->first();
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
     * @param int $id
     */
    public function deleteCustomer(int $id)
    {
        return $this->model->where('id', $id)->delete();
    }

    /**
     * Cancel subscription
     * @param string $user_uuid
     */
    public function cancelSubscription($user_uuid)
    {
        return $this->model->updateOrCreate(['user_uuid' => $user_uuid], ['subscription_active' => 0, 'subscription_plan_id' => 1]);
    }

    /**
     * Update subscription
     * @param string $user_uuid
     * @param int $priceId
     */
    public function updateSubscription($user_uuid, $priceId)
    {
        return $this->model->updateOrCreate(['user_uuid' => $user_uuid], ['subscription_active' => 1, 'subscription_plan_id' => $priceId]);
    }
}
