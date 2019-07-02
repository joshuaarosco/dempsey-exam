<?php

namespace App\Laravel\Requests\Backoffice;

use App\Laravel\Requests\RequestManager;
use Illuminate\Validation\Rule;

class ResponseMessageRequest extends RequestManager
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

        $id = $this->route('id')?:0;

        $rules = [
            'content' => "required",
            'code' => "required|unique:response_message,code,".$id,
        ];

        return $rules;
    }

    public function messages() {
        return [];
    }
}
