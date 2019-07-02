<?php

namespace App\Laravel\Requests\Api;

use App\Laravel\Requests\ApiRequestManager;
// use JWTAuth;

class PasswordRequest extends ApiRequestManager
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
            'new_password' => "required|confirmed",
            'current_password' => "required|old_password:" . $user->id,
        ];

        return $rules;
    }
}
