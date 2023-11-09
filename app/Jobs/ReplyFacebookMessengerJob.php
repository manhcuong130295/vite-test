<?php

namespace App\Jobs;

use App\Services\MessengerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReplyFacebookMessengerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $entries;

    /**
     * Create a new job instance.
     */
    public function __construct(string $id, array $entries)
    {
        $this->id = $id;
        $this->entries = $entries;
    }

    /**
     * Execute the job.
     */
    public function handle(MessengerService $messengerService): void
    {
        $messengerService->handleMessengerRequest($this->id, $this->entries);
    }
}

