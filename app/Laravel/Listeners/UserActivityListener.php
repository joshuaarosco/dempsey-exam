<?php

namespace App\Laravel\Listeners;

use App\Laravel\Events\UserAction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon;

class UserActivityListener implements ShouldQueue
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
     * @param  UserAction  $event
     * @return void
     */
    public function handle(UserAction $event)
    {
        $user = $event->user;
        $user->last_activity = Carbon::now();
        $user->save();
    }
}
