<?php

namespace App\Laravel\Notifications\Wishlist;

use App\Laravel\Models\Wishlist;
use App\Laravel\Notifications\MainNotification;
use Helper;

class WishlistCreatedNotification extends MainNotification
{

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Wishlist $wishlist)
    {
        $owner = $wishlist->owner ? $wishlist->owner->name : '';

        $data = [
            'type' => "WISHLIST",
            'reference_id' => $wishlist->id,
            'title' => Helper::get_response_message('WISHLIST_CREATED_NOTIFICATION_TITLE', ['name' => $owner, 'wishlist' => $wishlist->title]),
            'content' => Helper::get_response_message('WISHLIST_CREATED_NOTIFICATION_CONTENT', ['name' => $owner, 'wishlist' => $wishlist->title, 'category' => $wishlist->category]),
            'thumbnail' => $wishlist->getThumbnail(),
        ];

        $this->setData($data);
    }
}
