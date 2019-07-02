<?php

namespace App\Laravel\Notifications\Self\WishlistTransaction;

use App\Laravel\Models\WishlistTransaction;
use App\Laravel\Notifications\SelfNotification;
use Helper;

class GiftReceivedNotification extends SelfNotification
{

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(WishlistTransaction $wishlist_transaction)
    {
        $sender = $wishlist_transaction->sender ? $wishlist_transaction->sender->name : '';
        $wishlist = $wishlist_transaction->wishlist_title;

        $data = [
            'type' => "WISHLIST_TRANSACTION",
            'reference_id' => $wishlist_transaction->id,
            'title' => Helper::get_response_message("SELF_GIFT_RECEIVED_NOTIFICATION_TITLE"),
            'content' => Helper::get_response_message("SELF_GIFT_RECEIVED_NOTIFICATION_CONTENT", ['name' => $sender, 'wishlist' => $wishlist]),
            'thumbnail' => $wishlist_transaction->getThumbnail(),
        ];

        $this->setData($data);
    }
}
