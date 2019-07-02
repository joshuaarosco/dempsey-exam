<?php

namespace App\Laravel\Controllers\Backoffice\Auth;

use App\Laravel\Controllers\Controller;
use App\Laravel\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Helper;

class RegisterController extends Controller {

	protected $data = array();

	public function __construct() {
		$this->middleware('backoffice.guest');
	}

	public function register() {
		return view('backoffice.auth.register', $this->data);
	}

	public function store(Request $request) {
		$validator = Validator::make($request->all(), [
			'name' => "required",
			'email' => "required|email|unique:user,email",
			'password' => "required|confirmed",
		]);

		if($validator->fails()) {
			session()->flash('notification-status', "error");
			session()->flash('notification-msg', "Incomplete / invalid input.");
			return redirect()->back()
				->withErrors($validator)
				->withInput($request->except('password'));
		}

		
		$user = new User;
		$user->fill($request->all());
		$user->username = Helper::create_username($user->name);
		$user->password = bcrypt($request->password);
		$user->save();

		Auth::loginUsingId($user->id);
		session()->flash('notification-status', "success");
		session()->flash('notification-msg', "Welcome to " . config('app.name') . ".");
		return redirect()->route('backoffice.index');
	}

}