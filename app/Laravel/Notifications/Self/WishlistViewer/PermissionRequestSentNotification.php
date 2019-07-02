<?php

namespace App\Laravel\Notifications\Self\WishlistViewer;

use App\Laravel\Models\WishlistViewer;
use App\Laravel\Notifications\SelfNotification;
use Helper;

class PermissionRequestSentNotification extends SelfNotification
{

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(WishlistViewer $wishlist_viewer)
    {
        $owner = $wishlist_viewer->owner ? $wishlist_viewer->owner->name : '';
        $wishlist = $wishlist_viewer->wishlist ? $wishlist_viewer->wishlist->title : '';
        $thumbnail = $wishlist_viewer->owner ? $wishlist_viewer->owner->getAvatar() : '';

        $data = [
            'type' => "WISHLIST",
            'reference_id' => $wishlist_viewer->wishlist_id,
            'title' => Helper::get_response_message("SELF_PERMISSION_REQUEST_SENT_NOTIFICATION_TITLE", ['name' => Helper::str_contract($owner)]),
            'content' => Helper::get_response_message("SELF_PERMISSION_REQUEST_SENT_NOTIFICATION_CONTENT", ['name' => Helper::str_contract($owner)]),
            'thumbnail' => $thumbnail,
        ];

        $this->setData($data);
    }
}
