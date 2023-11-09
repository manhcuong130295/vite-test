<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * UserService constructor
     *
     * @param UserRepository $userRepository
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get list user
     *
     * @param string|array $columns
     * @param string|array $relations
     *
     * @return Collection
     */
    public function listUser($columns = ['*'], $relations = ['projects']): Collection
    {
        return $this->userRepository->list($columns, $relations);
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
        return $this->userRepository->detail($id);
    }
}
