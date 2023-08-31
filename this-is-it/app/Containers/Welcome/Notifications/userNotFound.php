<?php

namespace App\Containers\Welcome\Notifications;

use App\Ship\Parents\Notifications\Notification;
use Illuminate\Bus\Queueable;

/**
 * Class userNotFound
 */
class userNotFound extends Notification
{
    use Queueable;

    protected $notificationMessage;

    public function __construct($notificationMessage)
    {
        $this->notificationMessage = $notificationMessage;
    }

    public function toArray($notifiable)
    {
        return [
            'content' => $this->notificationMessage,
        ];
    }
}
