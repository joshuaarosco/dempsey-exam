<?php

namespace App\Laravel\Middleware\Backoffice;

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
        if ( !Auth::check() ) {
            
            $redirect_uri = $request->url();
            $redirect_key = base64_encode($redirect_uri);
            session()->put($redirect_key, $redirect_uri);

            return redirect()->route('backoffice.auth.login', [$redirect_key]);
        }

        return $next($request);
    }
}
