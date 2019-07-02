<?php

namespace App\Laravel\Requests\Api;

use App\Laravel\Requests\ApiRequestManager;
// use JWTAuth;

class ProfileRequest extends ApiRequestManager
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = $this->auth;

        $rules = [
            'name' => "required",
            'email' => "required|email|unique:user,email," . $user->id,
            // 'password' => "required|old_password:" . $user->id,
        ];

        if($this->has('birthdate')) {
            $rules['birthdate'] = "date";
        }

        if($this->has('contact_number')) {
            $rules['contact_number'] = "phone:PH,mobile";
            // $rules['country'] = "required_with:contact_number";
        }

        if($user->password) {
            $rules['password'] = "required|old_password:" . $user->id;
        }

        return $rules;
    }

    public function messages() {
        return [
            'password.old_password' => "Incorrect password.",
            'contact_number.phone' => "This mobile number is in incorrect format.",
        ];
    }
}
