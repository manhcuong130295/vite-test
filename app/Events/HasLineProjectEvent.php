<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HasLineProjectEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $channel;

    public function __construct($channel)
    {
        $this->channel = $channel;
    }
}
