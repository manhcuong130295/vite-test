<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HasFacebookFanpageEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $page;

    public function __construct($page)
    {
        $this->page = $page;
    }
}
