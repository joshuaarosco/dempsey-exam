<?php namespace App\Laravel\Requests\Backoffice;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class EmployeeRequest extends RequestManager{

	public function rules(){

		$id = $this->segment(3)?:0;

		$rules = [
			'fname' => "required",
			'lname' => "required",
			'email' => "required",
			'contact_number' => "required",
			'file' => "required",
			'birthdate' => "required",
		];

		return $rules;
	}

	public function messages(){
		return [
			'required' => "Field is required.",
			'email.unique' => "Email is already in use.",
			'password.between' => "Password must be min. of 6 and max. of 25 characters.",
			'password.confirmed' => "Password does not match.",
		];
	}
}