<?php

namespace App\Laravel\Middleware\Api;

use Carbon, Closure, DB, Helper;

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
        $token = DB::table('password_resets')
                    ->where('email', $request->email)
                    ->where('token', $request->token)
                    ->first();

        if(!$token) {
        	return response()->json([
                'msg' => Helper::get_response_message("INVALID_RESET_TOKEN"),
                'status' => FALSE,
                'status_code' => "INVALID_RESET_TOKEN",
            ], 421);
        }

        if(Carbon::parse($token->created_at)->addMinutes(60)->isPast()) {
        	return response()->json([
                'msg' => Helper::get_response_message("INVALID_RESET_TOKEN"),
                'status' => FALSE,
                'status_code' => "INVALID_RESET_TOKEN",
            ], 421);
        }

        return $next($request);
    }
}
