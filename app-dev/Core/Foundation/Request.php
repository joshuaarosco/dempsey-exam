<?php

namespace Core\Foundation;

use Core\Traits\SendsResponseData;
use Illuminate\Support\Facades\Lang;
use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    use SendsResponseData;

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
     * Override Illuminate\Foundation\Http\FormRequest@response method
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function response(array $errors)
    {
        if ($this->expectsJson() OR $this->segment(1) == "api") {
            return $this->sendFailedInputResponse($errors);
        }

        session()->flash('notification-status','error');
        session()->flash('notification-msg','Some fields are not accepted.');

        return $this->redirector->to($this->getRedirectUrl())
                                        ->withInput($this->except($this->dontFlash))
                                        ->withErrors($errors, $this->errorBag);
    }

    /**
     * Send an input failed response for a request that expects a json/xml response
     *
     * @return \Illuminate\Html\Response
     */
    public function sendFailedInputResponse($errors)
    {
        return $this->sendResponseData([
            'msg' => Lang::get("Api::error.input"),
            'status_code' => "INVALID_INPUT",
            'status' => FALSE,
            'errors' => $errors,
        ], 422);
    }
}
