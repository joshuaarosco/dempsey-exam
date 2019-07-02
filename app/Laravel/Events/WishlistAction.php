<?php

namespace App\Laravel\Events;

use App\Laravel\Models\User;
use App\Laravel\Models\Wishlist;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Input;

class WishlistAction
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $wishlist;
    public $action;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Wishlist $wishlist, $action = "")
    {
        $this->user = $user;
        $this->wishlist = $wishlist;
        $this->action = $action;
    }

}
