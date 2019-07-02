<?php

namespace App\Laravel\Middleware\Frontend;

use Closure, Session;
use Illuminate\Support\Facades\Auth;

class Authenticate
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
        if ( !Auth::check() OR in_array(Auth::user()->type, ['super_user'])) {
            
            $redirect_uri = $request->url();
            $redirect_key = base64_encode($redirect_uri);
            session()->put($redirect_key, $redirect_uri);
            Session::flash('notification-status','failed');
            Session::flash('notification-msg',"Please login to continue the page your are trying to access.");
            Auth::logout();

            return redirect()->route('frontend.auth.login', [$redirect_key]);
        }

        return $next($request);
    }
}
