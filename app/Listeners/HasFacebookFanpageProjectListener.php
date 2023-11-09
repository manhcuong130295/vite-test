<?php

namespace App\Listeners;

use App\Events\HasFacebookFanpageEvent;
use App\Notifications\HasFacebookFanpageProjectNotification;
use Illuminate\Support\Facades\Notification;

class HasFacebookFanpageProjectListener
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
    public function handle(HasFacebookFanpageEvent $event): void
    {
        $notify = new HasFacebookFanpageProjectNotification($event);
        Notification::route('slack', config('notifications.SLACK_BOT_USER_DEFAULT_CHANNEL'))->notify($notify);
    }
}
