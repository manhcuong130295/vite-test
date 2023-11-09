<?php

namespace App\Notifications;

use App\Constants\SubscriptionStatus;
use App\Events\HasSubscriptionEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Support\Facades\Auth;

class HasSubscriptionNotification extends Notification
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     * @param HasSubscriptionEvent $event
     */
    public function __construct(HasSubscriptionEvent $event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(): array
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack(): SlackMessage
    {
        $notification = (new SlackMessage)
            ->content("*".$this->event->message. "*")
            ->attachment(function ($attachment) {
                $attachment->content("Information user subscription")
                    ->footer('Time subscription: ' . now());
                    if (!empty($this->event->user_uuid)) {
                        $attachment->field('UUID', "`" . $this->event->user_uuid . "`");
                    } elseif (!empty(Auth::user())) {
                        $attachment->field('UUID', "`" . Auth::user()->uuid . "`")
                                   ->field('Username', "`" . Auth::user()->name . "`");
                    }
            });

        $notification = $notification->success();
        if ($this->event->status === SubscriptionStatus::FAIL) {
            $notification = $notification->error();
        }

        return $notification;
    }
}
