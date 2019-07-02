<?php namespace App\Laravel\Requests\Backoffice;

use Session,Auth, Input;
use App\Laravel\Requests\RequestManager;

class UserSettingRequest extends RequestManager{

	public function rules(){

		$rules = [
			'fname' => "required",
			'lname' => "required",
			'username' => "required|unique_username:".Auth::user()->id,
			'email' => "required|is_email_exist:".Auth::user()->id,
			'contact' => "required",
			'address' => "required",
			'file' => "image",
		];

		return $rules;
	}

	public function messages(){
		return [
			'required' => "This field is required.",
		];
	}
}