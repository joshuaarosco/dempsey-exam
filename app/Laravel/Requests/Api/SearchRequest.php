<?php

namespace App\Laravel\Requests\Api;

use App\Laravel\Requests\ApiRequestManager;

class SearchRequest extends ApiRequestManager
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'keyword' => "required",
        ];

        return $rules;
    }

    public function messages() {
        return [
            'password.old_password' => "Incorrect password.",
        ];
    }
}
