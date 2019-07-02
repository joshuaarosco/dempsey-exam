<?php

namespace App\Laravel\Notifications\WishlistViewer;

use App\Laravel\Models\WishlistViewer;
use App\Laravel\Notifications\MainNotification;
use Helper;

class PermissionRequestSentNotification extends MainNotification
{

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(WishlistViewer $wishlist_viewer)
    {
        $viewer = $wishlist_viewer->viewer ? $wishlist_viewer->viewer->name : '';
        $wishlist = $wishlist_viewer->wishlist ? $wishlist_viewer->wishlist->title : '';
        $thumbnail = $wishlist_viewer->viewer ? $wishlist_viewer->viewer->getAvatar() : '';

        $data = [
            'type' => "WISHLIST_VIEWER",
            'reference_id' => $wishlist_viewer->id,
            'title' => Helper::get_response_message('PERMISSION_REQUEST_SENT_NOTIFICATION_TITLE', ['name' => $viewer]),
            'content' => Helper::get_response_message('PERMISSION_REQUEST_SENT_NOTIFICATION_CONTENT', ['name' => $viewer, 'wishlist' => $wishlist]),
            'thumbnail' => $thumbnail,
        ];
        
        $this->setData($data);
    }
}
