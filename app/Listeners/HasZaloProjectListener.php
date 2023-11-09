<?php

namespace App\Listeners;

use App\Events\HasZaloProjectEvent;
use App\Notifications\HasZaloProjectNotification;
use Illuminate\Support\Facades\Notification;

class HasZaloProjectListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(HasZaloProjectEvent $event): void
    {
        $notify = new HasZaloProjectNotification($event);
        Notification::route('slack', config('notifications.SLACK_BOT_USER_DEFAULT_CHANNEL'))->notify($notify);
    }
}
