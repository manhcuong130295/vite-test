<?php

namespace App\Console\Commands;

use App\Jobs\GetAmountOfMessageJob;
use App\Services\UserService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class GetMessageCountFromRedisCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:message-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get amount of messages success returned from Redis.';

    /**
     * @var UserService
     */
    protected $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = $this->userService->listUser(['id', 'uuid'], ['requestStatistics', 'customer']);
        GetAmountOfMessageJob::dispatch($users);
    }
}
