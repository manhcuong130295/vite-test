<?php

namespace App\Notifications;

use App\Events\HasZaloProjectEvent;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class HasZaloProjectNotification extends Notification
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(HasZaloProjectEvent $event)
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
        return (new SlackMessage)
            ->content("*A user has just created a channel on Zalo.*")
            ->success()
            ->attachment(function ($attachment) {
                $attachment->content("Information about a ZALO channel has been requested:")
                    ->field('UUID', "`" . $this->event->channel->uuid . "`")
                    ->field('Project name', "`" . $this->event->channel->name . "`")
                    ->footer('Time created at: ' . Carbon::parse($this->event->channel->created_at)->format('d/m/Y H:i'));
            });
    }
}
