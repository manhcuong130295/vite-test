<?php

namespace App\Jobs;

use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class GetAmountOfMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Collection
     */
    protected $users;

    /**
     * Create a new job instance.
     */
    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $now = now()->toDateTimeString();
            $channel = 'batch';
            $users = $this->users;
            Log::channel($channel)->info("Start get message count from redis to save into database at [$now]");
            $messageCounts = Redis::pipeline(function ($pipe) use ($users) {
                foreach ($users as $user) {
                    $userUuid = $user->uuid;
                    $pipe->hget("message_counts", "user:$userUuid");
                }
            });

            DB::beginTransaction();

            foreach ($users as $key => $user) {
                if (empty($messageCounts[$key])) {
                    continue;
                }

                $messageCount = $messageCounts[$key];
                $user->update(['message_count' => $messageCount]);
                $requestStatistic = $user->requestStatistics()->getQuery()
                    ->where(['subscription_plan_id' => $user->customer->subscription_plan_id])->latest()->first();

                if ($requestStatistic) {
                    $requestStatistic->update([
                        'total_request' => $messageCount
                    ]);
                }
            }

            DB::commit();
            Log::channel($channel)->info("Get message count from redis successfully");
        } catch (Exception $e) {
            DB::rollback();
            Log::channel($channel)->error(sprintf($e));
            Log::channel($channel)->error("Failed to get message count");
        }
    }
}
