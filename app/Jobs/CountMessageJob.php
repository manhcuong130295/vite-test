<?php

namespace App\Jobs;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class CountMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $currentMonth = Carbon::now()->month;
        $requestStatistic = $this->user->requestStatistics()->getQuery()->where([
            'month' => $currentMonth,
            'subscription_plan_id' => $this->user->customer->subscription_plan_id
        ])->latest()->first();

        if (!$requestStatistic) {
            Log::info("Reset count message in: $currentMonth");
            Redis::hset("message_counts", "user:{$this->user->uuid}", 0);

            Log::info("Create new record request statics in: $currentMonth");
            $this->createRequestStatistic($currentMonth);
        }

        $messageCount = Redis::hincrby("message_counts", "user:{$this->user->uuid}", 1);
        Log::info('Message count when return answer: ' . $messageCount);
    }

    /**
     * Create new record request static
     *
     * @param int $currentMonth
     */
    private function createRequestStatistic(int $currentMonth)
    {
        $this->user->requestStatistics()->create([
            'subscription_plan_id' => $this->user->customer->subscription_plan_id ?? 1,
            'total_request' => 0,
            'month' => $currentMonth
        ]);
    }
}
