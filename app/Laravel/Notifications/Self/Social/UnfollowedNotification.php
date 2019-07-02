<?php

namespace App\Laravel\Notifications\Self\Social;

use App\Laravel\Models\User;
use App\Laravel\Notifications\SelfNotification;
use Helper;

class UnfollowedNotification extends SelfNotification
{

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $following)
    {
        $data = [
            'type' => "USER",
            'reference_id' => $following->id,
            'title' => Helper::get_response_message("SELF_UNFOLLOWED_NOTIFICATION_TITLE", ['name' => $following->name]),
            'content' => Helper::get_response_message("SELF_UNFOLLOWED_NOTIFICATION_CONTENT", ['name' => $following->name]),
            'thumbnail' => $following->getAvatar(),
        ];

        $this->setData($data);
    }
}
