<?php

namespace App\Laravel\Notifications\Social;

use App\Laravel\Models\User;
use App\Laravel\Notifications\MainNotification;
use Helper;

class UnfollowedNotification extends MainNotification
{

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $follower)
    {
        $data = [
            'type' => "USER",
            'reference_id' => $follower->id,
            'title' => Helper::get_response_message("UNFOLLOWED_NOTIFICATION_TITLE"),
            'content' => Helper::get_response_message("UNFOLLOWED_NOTIFICATION_CONTENT", ['name' => $follower->name]),
            'thumbnail' => $follower->getAvatar(),
        ];

        $this->setData($data);
    }
}
