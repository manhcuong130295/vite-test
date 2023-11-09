<?php

namespace App\Events;

use App\Constants\SubscriptionStatus;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HasSubscriptionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $status;
    public $user_uuid;

    public function __construct($message, $status = SubscriptionStatus::SUCCESS, $user_uuid)
    {
        $this->message = $message;
        $this->status = $status;
        $this->user_uuid = $user_uuid;
    }
}
