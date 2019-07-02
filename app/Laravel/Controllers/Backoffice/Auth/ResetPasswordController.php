<?php

namespace App\Laravel\Controllers\Backoffice\Auth;

use App\Laravel\Controllers\Controller;
use App\Laravel\Models\User;
use App\Laravel\Models\PasswordReset;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller {

	protected $data = array();

	public function __construct() {
		$this->middleware(['backoffice.guest', 'backoffice.verify-reset-token']);
	}

	public function showResetPasswordView($token) {
		return view('backoffice.auth.reset-password', $this->data);
	}

	public function processPasswordResetToken(Request $request, $token) {

		$this->validate($request, [ 
			'password' => 'required'
		]);

		$password_reset = PasswordReset::where('token', $token)->first();

		$user = User::where('email', $password_reset->email)->first();
		$user->password = bcrypt($request->get('password'));
		$user->save();

		PasswordReset::where('token', $token)->delete();

		Auth::loginUsingId($user->id);
		session()->flash('notification-status', "success");
		session()->flash('notification-msg', "Your password has been updated. Welcome back.");
		return redirect()->route('backoffice.index');
	}

}