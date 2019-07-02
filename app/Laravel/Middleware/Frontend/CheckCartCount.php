<?php

namespace App\Laravel\Middleware\Frontend;

use Closure, Session, Cart, Helper;
use Illuminate\Support\Facades\Auth;

class CheckCartCount
{
     /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {   
        if(Auth::check()){
            if (Helper::cart('cart')->count() == 0) {
                Session::flash('notification-status','failed');
                Session::flash('notification-msg',"Your cart is empty. Kindly select an item before proceeding to checkout.");

                return redirect()->route('frontend.cart.index');
            }
        }else{
            Session::flash('notification-status','failed');
            Session::flash('notification-msg',"Please login first.");

            return redirect()->route('frontend.cart.index');
        }

        return $next($request);
    }
}
