<?php

namespace App\Listeners;

use App\Events\HasLineProjectEvent;
use App\Notifications\HasLineProjectNotification;
use Illuminate\Support\Facades\Notification;

class HasLineProjectListener
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
    public function handle(HasLineProjectEvent $event): void
    {
        $notify = new HasLineProjectNotification($event);
        Notification::route('slack', config('notifications.SLACK_BOT_USER_DEFAULT_CHANNEL'))->notify($notify);
    }
}
