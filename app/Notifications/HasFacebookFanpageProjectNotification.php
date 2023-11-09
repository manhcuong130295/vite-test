<?php

namespace App\Notifications;

use App\Events\HasFacebookFanpageEvent;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;

class HasFacebookFanpageProjectNotification extends Notification
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(HasFacebookFanpageEvent $event)
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
            ->content("*A user has just created a channel on FACEBOOK_FANPAGE.*")
            ->success()
            ->attachment(function ($attachment) {
                $attachment->content("Information about a FACEBOOK_FANPAGE channel has been requested:")
                    ->field('ID', "`" . $this->event->page->id . "`")
                    ->field('Page name', "`" . $this->event->page->name . "`")
                    ->field('Project id', "`" . $this->event->page->project_id . "`")
                    ->footer('Time created at: ' . Carbon::parse($this->event->page->created_at)->format('d/m/Y H:i'));
            });
    }
}