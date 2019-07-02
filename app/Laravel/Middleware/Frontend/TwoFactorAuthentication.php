<?php

namespace App\Laravel\Middleware\Frontend;

use Jenssegers\Agent\Agent;
use App\Laravel\Models\User;
use App\Laravel\Events\SendEmail;
use App\Laravel\Models\UserDevice;
use Carbon, Closure, DB, Auth,Request, Str, Event, Session;

class TwoFactorAuthentication
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
            if(Auth::user()->is_two_factor_authenticated == 'yes'){

                $agent = new Agent();

                $device = $agent->device();
                $model = $agent->platform();
                $os_version = $agent->version($model);
                if($agent->isDesktop()){
                    $type = "desktop";
                }else{
                    $type = "mobile/phone";
                }
                // $ip_address = Request::ip();

                $check_device = UserDevice::where('device',$device)
                    ->where('device_model',$model)
                    ->where('device_os_version',$os_version)
                    ->where('device_type',$type)
                    ->first();

                if(!$check_device){

                    $user = User::find(Auth::user()->id);
                    $user->authentication_code = Str::upper(Str::random($length=10));
                    $user->save();

                    $notification_data = new SendEmail(['input' => $user,'type' => "authentication"]);
                    Event::fire('send_email', $notification_data);

                    Session::flash('notification-status',"success");
                    Session::flash('notification-msg',"Your authentication code was sent to ".$user->email);

                    return redirect()->route('frontend.account.two_factor_authentication');
                }
            }
        }
        return $next($request);
    }
}
