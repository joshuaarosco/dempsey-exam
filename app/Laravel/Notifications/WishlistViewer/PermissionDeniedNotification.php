<?php

namespace App\Laravel\Notifications\WishlistViewer;

use App\Laravel\Models\WishlistViewer;
use App\Laravel\Notifications\MainNotification;
use Helper;

class PermissionDeniedNotification extends MainNotification
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
            'title' => Helper::get_response_message("PERMISSION_DENIED_NOTIFICATION_TITLE"),
            'content' => Helper::get_response_message("PERMISSION_DENIED_NOTIFICATION_CONTENT", ['name' => $owner, 'wishlist' => $wishlist]),
            'thumbnail' => $thumbnail,
        ];

        $this->setData($data);
    }
}
