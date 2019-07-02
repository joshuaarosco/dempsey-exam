<?php

namespace App\Laravel\Events;

use App\Laravel\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Input;

class UserAction
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $actions;
    public $request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, array $actions = [])
    {
        $this->user = $user;
        $this->actions = $actions;
        $this->request = collect(Input::all());
    }

}
