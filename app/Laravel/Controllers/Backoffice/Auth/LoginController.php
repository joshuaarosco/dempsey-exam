<?php

namespace App\Laravel\Controllers\Backoffice\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Laravel\Events\AuditTrailEvent;
use App\Laravel\Controllers\Controller;

class LoginController extends Controller {

	protected $data = array();

	public function __construct() {
		$this->middleware('backoffice.guest', ['except' => "logout"]);
	}

	public function login($redirect_uri = NULL) {
		return view('backoffice.auth.login', $this->data);
	}

	public function authenticate(Request $request, $redirect_uri = NULL) {
		$username = $request->get('username');
		$password = $request->get('password');
		$remember = $request->get('remember_me');

		$field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		if (Auth::attempt([$field => $username, 'password' => $password], $remember)) {
			session()->flash('notification-status', "success");
			session()->flash('notification-msg', "Welcome back!");

			if($redirect_uri AND session()->has($redirect_uri)){
				return redirect( session()->get($redirect_uri) );
			}

			return redirect()->route('backoffice.dashboard');
		}

		session()->flash('notification-status', "error");
		session()->flash('notification-msg', "Invalid username or password.");

		return redirect()->back();
	}

	public function logout() {
		Auth::logout();
		session()->flash('notification-status', "success");
		session()->flash('notification-msg', "You have been logged out from the system");
		return redirect()->route('backoffice.login');
	}

}