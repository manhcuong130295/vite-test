<?php

namespace App\Listeners;

use App\Events\HasExceptionEvent;
use App\Notifications\HasExceptionNotification;
use Illuminate\Support\Facades\Notification;

class HasExceptionListener
{
    public function handle(HasExceptionEvent $event)
    {
        $notify = new HasExceptionNotification($event);
        Notification::route('slack', config('notifications.SLACK_BOT_USER_DEFAULT_CHANNEL'))->notify($notify);
    }
}
