<?php

namespace App\Laravel\Requests\Backoffice;

use App\Laravel\Requests\RequestManager;
use Illuminate\Support\Facades\Auth;

class UserPasswordRequest extends RequestManager
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
            'new_password' => "required|confirmed",
            'current_password' => "required|old_password:" . $this->id ?:0,
        ];

        return $rules;
    }
}
