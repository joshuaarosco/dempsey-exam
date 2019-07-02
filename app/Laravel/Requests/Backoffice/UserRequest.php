<?php namespace App\Laravel\Requests\Backoffice;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class UserRequest extends RequestManager{

	public function rules(){

		$id = $this->segment(3)?:0;

		$rules = [
			'name' => "required",
			'email' => "required|unique:user,email,".$id,
			'password' => "required|confirmed|between:6,25",
		];

		if($id){
			unset($rules['password']);
		}

		return $rules;
	}

	public function messages(){
		return [
			'required' => "This item is required.",
			'email.unique' => "Email is already in use.",
			'password.between' => "Password must be min. of 6 and max. of 25 characters.",
			'password.confirmed' => "Password does not match.",
		];
	}
}