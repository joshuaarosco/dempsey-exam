<?php

namespace App\Laravel\Notifications;

use Helper;
use Illuminate\Bus\Queueable;
use App\Laravel\Notifications\Channels\BroadcastChannel;
use App\Laravel\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class PusherNotification extends Notification implements ShouldQueue
{
     use Queueable;

    /**
     * The notification data.
     *
     */
    protected $data = array();

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    public function __construct($data) {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ BroadcastChannel::class ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        $unread = $notifiable->unreadNotifications()
                    ->where(function($query) use($notifiable) {
                        if($notifiable->last_notification_check) {
                            $date = Helper::datetime_db($notifiable->last_notification_check);
                            $query->where('created_at', '>=', $date);
                        }
                    })
                    ->count();
                    
        return new BroadcastMessage(['unread' => $unread]);
    }
}
