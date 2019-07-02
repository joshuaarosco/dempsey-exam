<?php

namespace App\Laravel\Notifications\WishlistTransaction;

use App\Laravel\Models\WishlistTransaction;
use App\Laravel\Notifications\MainNotification;
use Helper;

class GiftReceivedNotification extends MainNotification
{

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(WishlistTransaction $wishlist_transaction)
    {
        $owner = $wishlist_transaction->owner ? $wishlist_transaction->owner->name : '';
        $wishlist = $wishlist_transaction->wishlist_title;

        $data = [
            'type' => "WISHLIST_TRANSACTION",
            'reference_id' => $wishlist_transaction->id,
            'title' => Helper::get_response_message("GIFT_RECEIVED_NOTIFICATION_TITLE", ['name' => $owner, 'wishlist' => $wishlist]),
            'content' => Helper::get_response_message("GIFT_RECEIVED_NOTIFICATION_CONTENT", ['name' => $owner, 'wishlist' => $wishlist]),
            'thumbnail' => $wishlist_transaction->getThumbnail(),
        ];

        $this->setData($data);
    }
}
