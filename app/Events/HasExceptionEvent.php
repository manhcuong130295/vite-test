<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HasExceptionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $exception;
    public $metas;

    public function __construct($exception, $metas = [])
    {
        $this->exception = $exception;
        $this->metas = $metas;
    }
}
