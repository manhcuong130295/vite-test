<?php

namespace App\Notifications;

use App\Events\HasLineProjectEvent;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class HasLineProjectNotification extends Notification
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(HasLineProjectEvent $event)
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
            ->content("*A user has just created a channel on LINE.*")
            ->success()
            ->attachment(function ($attachment) {
                $attachment->content("Information about a LINE channel has been requested:")
                    ->field('UUID', "`" . $this->event->channel->uuid . "`")
                    ->field('Project name', "`" . $this->event->channel->provider_name . "`")
                    ->field('Project id', "`" . $this->event->channel->project_id . "`")
                    ->footer('Time created at: ' . Carbon::parse($this->event->channel->created_at)->format('d/m/Y H:i'));
            });
    }
}
