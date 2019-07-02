<?php

namespace App\Laravel\Middleware\Frontend;

use App\Laravel\Models\User;

use Carbon, Closure, DB, Auth;

class CheckVerification
{
   /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user = User::find(Auth::user()->id);
            if($user->is_verify == 'no'){
                return redirect()->route('frontend.account.verification_message');
            }
        }

        return $next($request);
    }
}
