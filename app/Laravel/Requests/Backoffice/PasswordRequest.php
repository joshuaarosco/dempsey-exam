<?php 

namespace App\Laravel\Requests\Backoffice;

use App\Laravel\Requests\RequestManager;
use Auth;

class PasswordRequest extends RequestManager
{
    public function rules() {

        $user = Auth::user();

        $rules = [
            'current_password'  => "required|old_password:{$user->id}",
            'new_password'  => "required|between:8,25|confirmed",
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