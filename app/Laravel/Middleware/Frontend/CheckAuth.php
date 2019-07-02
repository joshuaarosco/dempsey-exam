<?php

namespace App\Laravel\Middleware\Frontend;

use Closure, Session;
use Illuminate\Support\Facades\Auth;

class CheckAuth
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
        if ( !Auth::check() OR !in_array(Auth::user()->type, ['member'])) {
            
            $redirect_uri = $request->url();
            $redirect_key = base64_encode($redirect_uri);
            // dd($redirect_key);
            session()->put($redirect_key, $redirect_uri);
            Session::flash('notification-status','failed');
            Session::flash('notification-msg',"Please login first to continue.");
            Auth::logout();

            return redirect()->route('frontend.auth.login', [$redirect_key]);
        }

        return $next($request);
    }
}
