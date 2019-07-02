<?php

namespace App\Laravel\Notifications\WishlistTransaction;

use App\Laravel\Models\WishlistTransaction;
use App\Laravel\Notifications\MainNotification;
use Helper;

class GiftSentNotification extends MainNotification
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
            'title' => Helper::get_response_message("GIFT_SENT_NOTIFICATION_TITLE"),
            'content' => Helper::get_response_message("GIFT_SENT_NOTIFICATION_CONTENT", ['name' => $sender, 'wishlist' => $wishlist]),
            'thumbnail' => $wishlist_transaction->getThumbnail(),
        ];

        $this->setData($data);
    }
}
