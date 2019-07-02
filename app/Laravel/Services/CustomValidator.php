<?php 

namespace App\Laravel\Services;

use App\Laravel\Models\User;
use Illuminate\Validation\Validator;
use Auth, Hash, Str, Helper;

class CustomValidator extends Validator {

    public function validateOldPassword($attribute, $value, $parameters){
        
        if($parameters){
            $user_id = $parameters[0];
            $user = User::find($user_id);
            return Hash::check($value,$user->password);
        }

        return FALSE;
    }

    public function validateUniqueUsername($attribute,$value,$parameters){
        $username = Str::lower($value);
        $user_id = FALSE;
        if($parameters){
            $user_id = $parameters[0];
        }

        if($user_id){
            $is_unique = User::where('id','<>',$user_id)->whereRaw("LOWER(username) = '{$username}'")->first();
        }else{
            $is_unique = User::whereRaw("LOWER(username) = '{$username}'")->first();
        }

        return $is_unique ? FALSE : TRUE;

    }

    public function validateIsEmailExist($attribute,$value,$parameters){
        $email = Str::lower($value);

        $is_email_exist = User::where('email',$email)->first();

        if($is_email_exist){
            return TRUE;
        }

        return FALSE;
    }

    public function validateCheckAge($attribute, $value, $parameters){
            
        if(strtotime(Helper::date_format($value,"Y-m-d")) <= strtotime(Helper::date_format((date("Y")-18)."-".date("m")."-".date("d"),"Y-m-d"))){
            return TRUE;
        }

        return FALSE;
    }

} 