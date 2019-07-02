<?php

namespace App\Laravel\Controllers\Backoffice\Auth;

use App\Laravel\Controllers\Controller;
use App\Laravel\Models\User;
use App\Laravel\Models\PasswordReset;
use App\Laravel\Notifications\SendResetToken;
use Illuminate\Http\Request;
use Carbon, Str;

class ForgotPasswordController extends Controller {

	protected $data = array();

	public function __construct() {
		$this->middleware('backoffice.guest');
	}

	public function showForgotPasswordView() {
		return view('backoffice.auth.forgot-password', $this->data);
	}

	public function sendPasswordResetToken(Request $request) {

		$this->validate($request, [ 
			'email' => 'required|email|exists:user,email'
		]);

		$email = $request->get('email');
		$token = $this->_generateResetToken();

		$user = User::where('email', $email)->first();
		$user->notify(new SendResetToken($token) );
		$this->_saveResetToken($user, $token);

		session()->flash('notification-status', "success");
		session()->flash('notification-msg', "Check your email for the link to reset your password.");
		return redirect()->route('backoffice.auth.login');
	}

	private function _generateResetToken() {
		$key = config('app.key');
        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        return hash_hmac('sha256', Str::random(40), $key);
	}

	private function _saveResetToken(User $user, $token) {
		PasswordReset::where('email', $user->email)->delete();

		$password_reset = new PasswordReset;
		$password_reset->email = $user->email;
		$password_reset->token = $token;
		$password_reset->created_at = Carbon::now();
		$password_reset->save();
	}

}