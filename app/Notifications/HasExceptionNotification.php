<?php

namespace App\Notifications;

use App\Events\HasExceptionEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Support\Facades\Auth;

class HasExceptionNotification extends Notification
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     * @param HasSubscriptionEvent $event
     */
    public function __construct(HasExceptionEvent $event)
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
        $stackTrace = $this->formatStackTrace($this->event->exception);
        $userUuid = Auth::user() ? Auth::user()->uuid : '';
        return (new SlackMessage)
            ->content("* Error Message: `". $this->event->exception->getMessage() ."`*")
            ->error()
            ->attachment(function ($attachment) use ($stackTrace, $userUuid) {
                $attachment
                    ->content(
                        "
                         Class message exception `".get_class($this->event->exception)."`
                         \n*Backtrace* ``` " . $stackTrace . " ```
                        "
                    )
                    ->fields([
                        'Line' => $this->event->exception->getLine(),
                        'File' => $this->event->exception->getFile(),
                    ])
                    ->field('UUID', "* " . $userUuid . "*")
                    ->footer('Time error occur: ' . now());
            });
    }

    /**
     * Get stack trace in exception
     *
     * @param  mixed  $exception
     * @return string
     */
    private function formatStackTrace($exception)
    {
        $stackTrace = '';
        foreach ($exception->getTrace() as $index => $trace) {
            $traceFile = isset($trace['file']) ? $trace['file'] : null;
            $traceLine = isset($trace['line']) ? $trace['line'] : null;
            $stackTrace .= "#$index " . $traceFile . ' (' . $traceLine . '): ' . $trace['function'] . "\n";
        }

        return $stackTrace;
    }
}
