<?php

namespace App\Laravel\Listeners;

use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReadSelfNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NotificationSent  $event
     * @return void
     */
    public function handle(NotificationSent $event)
    {   
        $user = $event->notifiable;
        if($event->channel == "database") {
            foreach ($user->unreadNotifications as $key => $notification) {
                if(strpos($notification->type, "App\Laravel\Notifications\Self") !== FALSE) {
                    $notification->markAsRead();
                }
            }
        }
    }
}
