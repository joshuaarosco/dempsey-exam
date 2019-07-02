<?php

namespace App\Laravel\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SelfNotification extends Notification implements ShouldQueue
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
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // return [
        //     'type' => $this->data->get('type'),
        //     'reference_id' => $this->data->get('reference_id'),
        //     'title' => $this->data->get('title'),
        //     'content' => $this->data->get('content'),
        //     'thumbnail' => $this->data->get('thumbnail'),
        // ];
        return $this->data->toArray();
    }
}
