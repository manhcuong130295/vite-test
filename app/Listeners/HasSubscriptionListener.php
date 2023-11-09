<?php

namespace App\Listeners;

use App\Events\HasExceptionEvent;
use App\Events\HasSubscriptionEvent;
use App\Notifications\HasExceptionNotification;
use App\Notifications\HasSubscriptionNotification;
use Illuminate\Support\Facades\Notification;

class HasSubscriptionListener
{
    public function handle(HasSubscriptionEvent $event)
    {
        $notify = new HasSubscriptionNotification($event);
        Notification::route('slack', config('notifications.SLACK_BOT_USER_DEFAULT_CHANNEL'))->notify($notify);
    }
}
