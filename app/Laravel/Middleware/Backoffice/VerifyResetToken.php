<?php

namespace App\Laravel\Middleware\Backoffice;

use Carbon, Closure, DB;

class VerifyResetToken
{
   /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = DB::table('password_resets')->where('token', $request->token)->first();

        if(!$token) {
        	session()->flash('notification-status', "error");
			session()->flash('notification-msg', "Invalid reset token");
			return redirect()->route('backoffice.auth.login');
        }

        if(Carbon::parse($token->created_at)->addMinutes(60)->isPast()) {
        	session()->flash('notification-status', "error");
			session()->flash('notification-msg', "Reset token has already expired.");
			return redirect()->route('backoffice.auth.login');
        }

        return $next($request);
    }
}
