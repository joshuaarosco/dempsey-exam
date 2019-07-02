<?php

namespace Api\Auth\Requests;

use Core\Foundation\Request;

class LoginRequest extends Request
{
	/**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'username' => "required",
            'password' => "required",
        ];

        return $rules;
    }
}