<?php

namespace App\Laravel\Notifications\Self\Wishlist;

use App\Laravel\Models\Wishlist;
use App\Laravel\Notifications\SelfNotification;
use Helper;

class WishlistCreatedNotification extends SelfNotification
{

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Wishlist $wishlist)
    {

        $thumbnail = $wishlist->filename ? $wishlist->getThumbnail() : '';

        $data = [
            'type' => "WISHLIST",
            'reference_id' => $wishlist->id,
            'title' => Helper::get_response_message("SELF_WISHLIST_CREATED_NOTIFICATION_TITLE", ['wishlist' => $wishlist->title]),
            'content' => Helper::get_response_message("SELF_WISHLIST_CREATED_NOTIFICATION_CONTENT", ['wishlist' => $wishlist->title, 'category' => $wishlist->category]),
            'thumbnail' => $thumbnail,
        ];

        $this->setData($data);
    }
}
