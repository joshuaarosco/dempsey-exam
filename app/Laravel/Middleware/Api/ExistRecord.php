<?php

namespace App\Laravel\Middleware\Api;

use App\Laravel\Models\User;
use App\Laravel\Models\Wishlist;
use App\Laravel\Models\WishlistTransaction;
use Closure, Helper;

class ExistRecord
{

    protected $format;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $record
     * @return mixed
     */
    public function handle($request, Closure $next, $record)
    {
        $this->format = $request->format;
        $response = array();

        switch (strtolower($record)) {
            case 'user':
                if(! $this->_exist_user($request)) {
                    $response = [
                        'msg' => Helper::get_response_message("USER_NOT_FOUND"),
                        'status' => FALSE,
                        'status_code' => "USER_NOT_FOUND",
                        'hint' => "Make sure the 'user_id' from your request parameter exists and valid."
                    ];
                }
            break;
            case 'wishlist':
                if(! $this->_exist_wishlist($request)) {
                    $response = [
                        'msg' => Helper::get_response_message("WISHLIST_NOT_FOUND"),
                        'status' => FALSE,
                        'status_code' => "WISHLIST_NOT_FOUND",
                        'hint' => "Make sure the 'wishlist_id' from your request parameter exists and valid.",
                    ];
                }
            break;
            case 'wishlist_transaction':
                if(! $this->_exist_wishlist_transaction($request)) {
                    $response = [
                        'msg' => Helper::get_response_message("GIFT_NOT_FOUND"),
                        'status' => FALSE,
                        'status_code' => "GIFT_NOT_FOUND",
                        'hint' => "Make sure the 'wishlist_transaction_id' from your request parameter exists and valid.",
                    ];
                }
            break;
            case 'notification':
                if(! $this->_exist_notification($request)) {
                    $response = [
                        'msg' => Helper::get_response_message("NOTIFICATION_NOT_FOUND"),
                        'status' => FALSE,
                        'status_code' => "NOTIFICATION_NOT_FOUND",
                        'hint' => "Make sure the 'notification_id' from your request parameter exists and valid."
                    ];
                }
            break;
        }

        if(empty($response)) {
            return $next($request);
        }

        switch ($this->format) {
            case 'json':
                return response()->json($response, 404);
            break;
            case 'xml':
                return response()->xml($response, 404);
            break;
        }
    }


    private function _exist_user($request) {
        $user = User::where('type', "user")->find( $request->get('user_id') );
        
        if($user) {
            $request->merge(['user_data' => $user]);
            return TRUE;
        }

        return FALSE;
    }

    private function _exist_notification($request) {
        $notification = $request->auth->notifications()->where('id', $request->get('notification_id'))->first();
        
        if($notification) {
            $request->merge(['notification_data' => $notification]);
            return TRUE;
        }

        return FALSE;
    }

    private function _exist_wishlist($request) {
        $wishlist = Wishlist::find( $request->get('wishlist_id') );
        
        if($wishlist) {
            $request->merge(['wishlist_data' => $wishlist]);
            return TRUE;
        }

        return FALSE;
    }

    private function _exist_wishlist_transaction($request) {
        $wishlist_transaction = WishlistTransaction::find( $request->get('wishlist_transaction_id') );
        
        if($wishlist_transaction) {
            $request->merge(['wishlist_transaction_data' => $wishlist_transaction]);
            return TRUE;
        }

        return FALSE;
    }
    
}