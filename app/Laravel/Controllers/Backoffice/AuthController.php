<?php 

namespace App\Laravel\Controllers\Backoffice;

use App\Laravel\Models\ActivityLog;

// use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use App\User as Account;

use Session, Input, Auth, Event, Request;

class AuthController extends Controller{

	protected $data;

	public function __construct(Guard $auth){
		$this->auth = $auth;
	}

	public function login(){
		$data['page_title'] = "Login";
		$data['page_class'] = "login-body";
		return view('backoffice.auth.login',$data);
	}

	public function authenticate(){
		try{
			$username = Input::get('username');
			$password = Input::get('password');
			$remember_me = Input::get('remember_me',0);
			$field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';	

			if($this->auth->attempt([$field => $username,'password' => $password], $remember_me)){
				if(!in_array($this->auth->user()->type, ["super_user","admin","guest","employee",'content_manager'])){

					$this->auth->logout();
					Session::flash('notification-status','failed');
					Session::flash('notification-title',"Unauthorized access!");
					Session::flash('notification-msg',"You don't have enough authorization access.");

					return redirect()->route('backoffice.auth.login');
				}

				Session::flash('notification-status','info');
				Session::flash('notification-title',"It's nice to be back");
				Session::flash('notification-msg',"Welcome {$this->auth->user()->lname}!");
				// $this->auth->user()->is_lock = "no";
				$this->auth->user()->save();
				return redirect()->intended('admin');
			}	

			Session::flash('notification-status','failed');
			Session::flash('notification-msg','Wrong username or password.');
			return redirect()->back();

		}catch(Exception $e){
			abort(500);
		}
	}

	public function destroy(){
		$this->auth->logout();
		Session::flash('notification-status','success');
		Session::flash('notification-msg','You are now signed off.');
		return redirect()->route('backoffice.auth.login');
	}

	public function lock() {
	  $user = Auth::user();

	  $user->is_lock = "yes";
	  $user->save();
	  $this->data['auth'] = $user;
	  return view('backoffice.auth.lock',$this->data);
	 }

	 public function unlock() {
	  try {
	   $user = Auth::user();
	   $password = Input::get('password');
	   $remember_me = Input::get('remember_me',0);

	   if($this->auth->attempt(['email' => $user->email,'password' => $password], $remember_me)){
	    $user->is_lock = "no";
	    $user->save();
	    Session::flash('notification-status','info');
	    Session::flash('notification-title',"It's nice to be back");
	    Session::flash('notification-msg',"Welcome {$this->auth->user()->name}!");
	    return redirect()->intended('/');
	   }

	   Session::flash('notification-status','failed');
	   Session::flash('notification-msg','Invalid password.');
	   return redirect()->back();

	  } catch (Exception $e) {
	   abort(500);
	  }
	 }
}