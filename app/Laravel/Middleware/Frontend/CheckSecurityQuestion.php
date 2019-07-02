<?php

namespace App\Laravel\Middleware\Frontend;

use App\Laravel\Models\Security\SecurityQuestion;

use Carbon, Closure, DB, Auth;

class CheckSecurityQuestion
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
            $check_security_question = SecurityQuestion::where('user_id',Auth::user()->id)->get();
            if($check_security_question->count() == 0){
                return redirect()->route('frontend.account.set_security_question');
            }
        }

        return $next($request);
    }
}
