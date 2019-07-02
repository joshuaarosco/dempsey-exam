<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'send_email' => [
            'App\Laravel\Listeners\SendEmailListener'
        ],
        'event_trigger' => [
            'App\Laravel\Listeners\EventTriggerListener'
        ],
        'pusher_event' => [
            'App\Laravel\Listeners\PusherEventListener'
        ],
        'audit_trail' => [
            'App\Laravel\Listeners\AuditTrailListener'
        ],
        'notification' => [
            'App\Laravel\Listeners\NotificationListener'
        ],
        'App\Laravel\Events\UserAction' => [
            'App\Laravel\Listeners\UserActivityListener',
            'App\Laravel\Listeners\UserLoginListener',
            'App\Laravel\Listeners\UserRegisterListener',
        ],
        'App\Laravel\Events\WishlistAction' => [
            'App\Laravel\Listeners\WishlistActionListener',
        ],
        'Illuminate\Notifications\Events\NotificationSent' => [
            'App\Laravel\Listeners\ReadSelfNotification',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
