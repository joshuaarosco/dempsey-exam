<?php

namespace App\Laravel\Notifications;

use Helper;
use Illuminate\Bus\Queueable;
use NotificationChannels\FCM\FCMMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Laravel\Notifications\Channels\BroadcastChannel;
use App\Laravel\Notifications\Messages\BroadcastMessage;

class MainNotification extends Notification implements ShouldQueue
{
     use Queueable;

    /**
     * The notification data.
     *
     */
    protected $data;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Set the notification data.
     *
     * @return void
     */
    protected function setData($data) {
        $this->data = collect($data);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [ 'database', 'fcm', BroadcastChannel::class ];
    }

    /**
     * Get the fcm representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toFCM($notifiable)
    {
        $notification = [
            'title' => $this->data->get('title'),
            'body' => $this->data->get('content'),
        ];

        $data = [
            'title' => $this->data->get('title'),
            'body' => $this->data->get('content'),
            'type' => $this->data->get('type'),
            'reference_id' => $this->data->get('reference_id'),
            'thumbnail' => $this->data->get('thumbnail'),
            'event' => get_class($this),
        ];

        return (new FCMMessage())
            ->notification($notification)
            ->data($data);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->data->toArray();
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
