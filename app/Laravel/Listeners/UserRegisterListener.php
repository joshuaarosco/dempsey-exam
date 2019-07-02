<?php

namespace App\Laravel\Listeners;

use App\Laravel\Events\UserAction;
use App\Laravel\Models\UserDevice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Carbon, Input;

class UserRegisterListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserAction $event
     * @return void
     */
    public function handle(UserAction $event)
    {
        if(in_array("register", $event->actions)) {

            $user = $event->user;
            $user->last_login = Carbon::now();
            $user->save();

            $request = $event->request;

            if($request->has('device_id')){

                $device = UserDevice::where('user_id', $user->id)->where('device_id',  $request->get('device_id'))->first();

                UserDevice::where('device_id', $request->get('device_id'))
                            ->where('user_id','<>',$user->id)
                            ->update(['is_login' => "0"]);
                            
                if(!$device){
                    $new_device = new UserDevice;
                    $new_device->user_id = $user->id;
                    $new_device->reg_id =  $request->get('device_reg_id');
                    $new_device->device_id =  $request->get('device_id');
                    $new_device->device_name =  $request->get('device_name');
                    $new_device->is_login = 1;
                    $new_device->save();
                }else{
                    $device->reg_id =  $request->get('device_reg_id');
                    $device->device_name =  $request->get('device_name');
                    $device->is_login = 1;
                    $device->save();
                }
            }
        }
    }

}
