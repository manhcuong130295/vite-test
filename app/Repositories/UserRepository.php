<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    /**
     * @param array|string $column
     * @param array|string $relation
     *
     * @return Collection
     */
    public function list($columns = ['*'], $relations): Collection
    {
        return User::query()->with($relations)->get($columns);
    }

    /**
     * Get detail user
     *
     * @param int $id
     *
     * @return User|null
     */
    public function detail(int $id): ?User
    {
        return User::query()->where('id', $id)
                            ->with(['customer', 'customer.subscriptionPlan', 'requestStatistics'])
                            ->first();
    }
}
