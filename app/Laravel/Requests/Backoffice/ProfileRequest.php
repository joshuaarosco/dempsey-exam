<?php 

namespace App\Laravel\Requests\Backoffice;

use App\Laravel\Requests\RequestManager;

use Auth;

class ProfileRequest extends RequestManager
{
    public function rules() {

        $user = Auth::user();

        $rules = [
            'fname'     => "required",
            'lname'     => "required",
            'username'  => "required|alpha_num|unique:user,username,{$user->id}",
            'email'     => "required|email|unique:user,email,{$user->id}",
            'password'  => "required|old_password:{$user->id}",
        ];


        return $rules;
    }

    public function messages() {
        return [
            'required'  => "Field is required.",
            'old_password' => "Incorrect password.",
        ];
    }
}