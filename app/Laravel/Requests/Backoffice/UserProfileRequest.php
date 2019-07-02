<?php

namespace App\Laravel\Requests\Backoffice;

use App\Laravel\Requests\RequestManager;
use Illuminate\Support\Facades\Auth;

class UserProfileRequest extends RequestManager
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        $rules = [
            'name' => "required",
            'email' => "required|email|unique:user,email," . $this->id ?:0,
            // 'password' => "required|old_password:" . $this->id ?:0,
        ];

        if($this->has('birthdate')) {
            $rules['birthdate'] = "date";
        }

        return $rules;
    }

    public function messages() {
        return [
            'password.old_password' => "Incorrect password.",
        ];
    }
}
