<?php 

namespace App\Laravel\Controllers\Frontend;

use App\Laravel\Models\User;
use App\Laravel\Models\Course;
use App\Laravel\Models\YearLevel;
use App\Laravel\Models\ActivityLog;
use App\Laravel\Models\StudentInfo;
use App\Laravel\Models\Municipality;
use App\Laravel\Models\StudentRequirement;


use Jenssegers\Agent\Agent;
use App\Laravel\Events\SendEmail;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use App\Laravel\Events\AuditTrailEvent;
use App\Laravel\Requests\Frontend\LoginRequest;
use App\Laravel\Requests\Frontend\RegisterRequest;
use App\Laravel\Requests\Frontend\NewPasswordRequest;
use App\Laravel\Requests\Frontend\ForgotPasswordRequest;
use App\User as Account;

use Session, Input, Auth, Str, Event, Carbon, Helper, Cart, Request, File, ImageUploader;

class AuthController extends Controller{

	protected $data;

	public function __construct(Guard $auth){
		$this->auth = $auth;
		$this->data['municipalities'] = ['' => "Choose Campus"] + Municipality::pluck('municipality_town','id')->toArray();

		$this->data['provinces'] = ['' => "Choose Province", 'bulacan' => "Bulacan", 'pampanga' => "Pampanga"];
	}

	/*
	To view the login page
	*/
	public function login(){
		$this->data['page_title'] = "Login | ReferApps";
		return view('frontend._pages.auth.login',$this->data);
	}

	/*
	Account authentication 
	*/
	public function authenticate(LoginRequest $request){
		$username = Input::get('username');
		$password = Input::get('password');
		$remember_me = Input::get('remember_me',0);
		$field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		if($this->auth->attempt([$field => $username,'password' => $password], $remember_me)){
			$this->auth->user()->save();

			$new_activity_log = new ActivityLog;
			$new_activity_log->user_id = Auth::user()->id;
			$new_activity_log->action = "login";
			$new_activity_log->description = 'Student '.Auth::user()->fname.' '.Auth::user()->lname.' was successully logged in.';
			$new_activity_log->ip_address = Request::ip();
			$new_activity_log->save();

			Session::flash('notification-status','success');
			Session::flash('notification-title',"It's nice to be back");
			Session::flash('notification-msg',"Welcome {$this->auth->user()->fname} {$this->auth->user()->lname}!");
			return redirect()->back();
		}

		$new_activity_log = new ActivityLog;
		$new_activity_log->user_id = 0;
		$new_activity_log->action = "login";
		$new_activity_log->description = 'Student failed to login.';
		$new_activity_log->ip_address = Request::ip();
		$new_activity_log->save();

		Session::flash('notification-status','failed');
		Session::flash('notification-msg','Wrong username or password.');
		return redirect()->back();
	}

	/*
	To view the registration page
	*/
	public function register(){
		$this->data['page_title'] = "Sign Up | BPC OSAMS";
		return view('frontend.auth.register',$this->data);
	}

	public function get_municipalities(){
		$province = Input::get('_province');
		$municipalities = [
			'bulacan' => [
				'Angat',
				'Balagtas', 
				'Baliuag',
				'Bocaue',
				'Bulacan',
				'Bustos',	
				'Calumpit',
				'Dona Remedios Trinidad',
				'Guiguinto',
				'Hagonoy',
				'Malolos',
				'Marilao',
				'Maycauayan',
				'Norzagaray',	
				'Obando',	
				'Pandi',	
				'Paombong',	
				'Plaridel',	
				'Pulilan',	
				'San Ildefonso',	
				'San Jose del Monte',	
				'San Miguel',	
				'San Rafael',	
				'Santa Maria',	
				'Sapang Palay',
			],
			'pampanga' => [
				'Angeles City',
				'Apalit',
				'Arayat',
				'Bacolor',
				'Basa Air Base',
				'Candaba',
				'Floridablanca',
				'Guagua',
				'Lubao',
				'Mabalacat',
				'Macabebe',
				'Magalang',
				'Masantol',
				'Mexico',
				'Minalin',
				'Porac',
				'San Fernando',
				'San Luis',
				'San Simon',
				'Santa Ana',
				'Santa Rita',
				'Santo Tomas',
				'Sasmuan',
			],
		];

		$data['municipalities'] = $municipalities[$province];
		return response()->json($data,200);
	}

	public function get_zip_code(){
		$municipality = Input::get('_municipality');
		$zip_codes = [
		'Angat' => '3012',
		'Balagtas' => '3016',
		'Baliuag' => '3006',
		'Bocaue' => '3018',
		'Bulacan' => '3017',
		'Bustos' => '3007',
		'Calumpit' => '3003',
		'Dona Remedios Trinidad' => '3009',
		'Guiguinto' => '3015',
		'Hagonoy' => '3002',
		'Malolos' => '3000',
		'Marilao' => '3019',
		'Maycauayan' => '3020',
		'Norzagaray' => '3013',
		'Obando' => '3021',
		'Pandi' => '3014',
		'Paombong' => '3001',
		'Plaridel' => '3004',
		'Pulilan' => '3005',
		'San Ildefonso' => '3010',
		'San Jose del Monte' => '3023',
		'San Miguel' => '3011',
		'San Rafael' => '3008',
		'Santa Maria' => '3022',
		'Sapang Palay' => '3024',
		'Angeles City' =>	'2009',
		'Apalit' =>	'2016',
		'Arayat' =>	'2012',
		'Bacolor' =>	'2001',
		'Basa Air Base' =>	'2007',
		'Candaba' =>	'2013',
		'Floridablanca' =>	'2006',
		'Guagua' =>	'2003',
		'Lubao' =>	'2005',
		'Mabalacat' =>	'2010',
		'Macabebe' =>	'2018',
		'Magalang' =>	'2011',
		'Masantol' =>	'2017',
		'Mexico' =>	'2021',
		'Minalin' =>	'2019',
		'Porac' =>	'2008',
		'San Fernando' =>	'2000',
		'San Luis' =>	'2014',
		'San Simon' =>	'2015',
		'Santa Ana' =>	'2022',
		'Santa Rita' =>	'2002',
		'Santo Tomas' =>	'2020',
		'Sasmuan' =>	'2004',];
		$data['zip_code'] = $zip_codes[$municipality];
		return response()->json($data,200);
	}

	/*
	Used to create new user account
	*/
	public function create(RegisterRequest $request){
		$new_user = new User;
		$new_user->fill($request->all());
		$new_user->birthdate = Helper::date_format($request->birthdate,'Y-m-d');
		if($new_user->save()){
			$new_stud_info = new StudentInfo;
			$new_stud_info->user_id = $new_user->id;
			$new_stud_info->fill($request->all());
			$new_stud_info->save();

			$inputs = Input::all();

			$new_requirements = new StudentRequirement;
			$new_requirements->user_id = $new_user->id;

			if(Input::hasFile('id_file')){
				$upload = ImageUploader::upload($inputs['id_file'],'storage/requirements');
				$new_requirements->path = $upload["path"];
				$new_requirements->directory = $upload["directory"];
				$new_requirements->filename = $upload["filename"];
			}

			if(Input::hasFile('cor_file')){
				$upload = ImageUploader::upload($inputs['cor_file'],'storage/requirements');
				$new_requirements->cor_path = $upload["path"];
				$new_requirements->cor_directory = $upload["directory"];
				$new_requirements->cor_filename = $upload["filename"];
			}

			if(Input::hasFile('or_file')){
				$upload = ImageUploader::upload($inputs['or_file'],'storage/requirements');
				$new_requirements->or_path = $upload["path"];
				$new_requirements->or_directory = $upload["directory"];
				$new_requirements->or_filename = $upload["filename"];
			}

			if(Input::hasFile('si_file')){
				$upload = ImageUploader::upload($inputs['si_file'],'storage/requirements');
				$new_requirements->si_path = $upload["path"];
				$new_requirements->si_directory = $upload["directory"];
				$new_requirements->si_filename = $upload["filename"];
			}

			if(Input::hasFile('la_file')){
				$upload = ImageUploader::upload($inputs['la_file'],'storage/requirements');
				$new_requirements->la_path = $upload["path"];
				$new_requirements->la_directory = $upload["directory"];
				$new_requirements->la_filename = $upload["filename"];
			}

			if(Input::hasFile('bi_file')){
				$upload = ImageUploader::upload($inputs['bi_file'],'storage/requirements');
				$new_requirements->bi_path = $upload["path"];
				$new_requirements->bi_directory = $upload["directory"];
				$new_requirements->bi_filename = $upload["filename"];
			}

			if(Input::hasFile('cc_file')){
				$upload = ImageUploader::upload($inputs['cc_file'],'storage/requirements');
				$new_requirements->cc_path = $upload["path"];
				$new_requirements->cc_directory = $upload["directory"];
				$new_requirements->cc_filename = $upload["filename"];
			}
			
			$new_requirements->save();

			Session::flash('notification-status','success');
			Session::flash('notification-msg','Your application was successfully submitted and currently on the review process.');

			return redirect()->back();
		}
	}

	/*
	Used to logout the user
	*/
	public function destroy(){
		$this->auth->logout();
		Session::flash('notification-status','success');
		Session::flash('notification-msg','You are now signed off.');
		return redirect()->route('frontend.index');
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
				Session::flash('notification-msg',"Welcome {$this->auth->user()->fname} {$this->auth->user()->lname}!");
				return redirect()->intended('/');
			}

			Session::flash('notification-status','failed');
			Session::flash('notification-msg','Invalid password.');
			return redirect()->back();

		} catch (Exception $e) {
			abort(500);
		}
	}

	/*
	Used for facebook login or signup
	*/
	public function fb_login(){
		$fname = Input::get('_fname');
		$lname = Input::get('_lname');
		$email = Input::get('_email');

		$user = User::where('email',$email)->first();

		if($user){
			$data['status_code'] = "EMAIL_ALREADY_EXIST";
            $data['status'] = "login_success";
            $data['title'] = "Welcome!";
            $data['msg'] = "User already been signed up.";
            $data['data'] = $user;

            Auth::loginUsingId($user->id, true);

            goto callback;
		}else{
			$bytes = openssl_random_pseudo_bytes(6);
			$hex = Str::upper(bin2hex($bytes));
			$new_user = new User;

			$new_user->fname = $fname;
			$new_user->lname = $lname;
			$new_user->email = $email;
			$new_user->username = $email;
			$new_user->country_code = 'PH';
			$new_user->refer_id = $hex;
			$new_user->save();

			$notification_data = new SendEmail(['input' => $new_user,'type' => "verification"]);
			Event::fire('send_email', $notification_data);

			$data['status_code'] = "SINED_UP_SUCCESSFULLY";
            $data['status'] = "login_success";
            $data['title'] = "Welcome!";
            $data['msg'] = "User successfully login.";
            $data['data'] = $new_user;

            Auth::loginUsingId($new_user->id, true);

            goto callback;
		}
		
		callback:

		return response()->json($data,200);
	}

	/*
	Used for google login or signup
	*/
	public function google_login(){
		$google_id = Input::get('_id');
		$fname = Input::get('_fname');
		$lname = Input::get('_lname');
		$email = Input::get('_email');

		$user = User::where('email',$email)->first();
		if($user){
			$data['status_code'] = "EMAIL_ALREADY_EXIST";
            $data['status'] = "login_success";
            $data['title'] = "Welcome!";
            $data['msg'] = "User already been signed up.";
            $data['data'] = $user;

            Auth::loginUsingId($user->id, true);

            goto callback;
		}else{
			$bytes = openssl_random_pseudo_bytes(6);
			$hex = Str::upper(bin2hex($bytes));
			$new_user = new User;

			$new_user->fname = $fname;
			$new_user->lname = $lname;
			$new_user->email = $email;
			$new_user->username = $email;
			$new_user->country_code = 'PH';
			$new_user->refer_id = $hex;
			$new_user->save();

            $notification_data = new SendEmail(['input' => $new_user,'type' => "verification"]);
			Event::fire('send_email', $notification_data);

			$data['status_code'] = "SINED_UP_SUCCESSFULLY";
            $data['status'] = "login_success";
            $data['title'] = "Welcome!";
            $data['msg'] = "User successfully login.";
            $data['data'] = $new_user;

            Auth::loginUsingId($new_user->id, true);

            goto callback;

		}

		callback:

		return response()->json($data,200);
	}

	/*
	View the forgot password page
	*/
	public function forgot_password(){
		$this->data['page_title'] = "Forgot Password | ReferApps";
		return view('frontend._pages.auth.forgot_password',$this->data);
	}

	/*
	Search if the user account is existing
	*/
	public function search(ForgotPasswordRequest $request){
		$user = User::where('email',$request->email)->first();

		if(!$user){
			Session::flash('notification-status','failed');
			Session::flash('notification-msg','No search result. Your search did not return any results.');
			return redirect()->back();
		}

		$bytes = openssl_random_pseudo_bytes(3);
		$hex = Str::upper(bin2hex($bytes));

		$user->verification_code = $hex;

		$user->save();

		$notification_data = new SendEmail(['input' => $user,'type' => "password_reset_code"]);
		Event::fire('send_email', $notification_data);

		return redirect()->route('frontend.auth.password_reset_verification',['u' => base64_encode(base64_encode($user->username))]);
	}

	/*
	Password reset verification code page
	*/
	public function password_reset_verification($u){
		$this->data['page_title'] = "Password Reset Code| ReferApps";
		$username = base64_decode(base64_decode($u));
		$this->data['u'] = $username;
		return view('frontend._pages.auth.password_reset_verification',$this->data);
	}

	/*
	Verifying if the reset code is valid or not
	*/
	public function verify($u){
		$username = base64_decode(base64_decode($u));

		$user = User::where('username',$username)->first();
		$code = Input::get('code');

		if($code == $user->verification_code){
			return redirect()->route('frontend.auth.reset_password',['u' => base64_encode(base64_encode($user->username))]);
		}else{
			Session::flash('notification-status','failed');
			Session::flash('notification-msg','Sorry you entered the wrong password reset code. We will send you a new reset code for security purposes');

			$bytes = openssl_random_pseudo_bytes(3);
			$hex = Str::upper(bin2hex($bytes));

			$user->verification_code = $hex;

			$user->save();

			$notification_data = new SendEmail(['input' => $user,'type' => "password_reset_code"]);
			Event::fire('send_email', $notification_data);

			return redirect()->back();
		}
	}

	/*
	Reset password form
	*/
	public function reset_password($u){
		$this->data['page_title'] = "Create New Password| ReferApps";
		$username = base64_decode(base64_decode($u));
		$this->data['u'] = $username;
		return view('frontend._pages.auth.reset_password',$this->data);
	}

	/*
	Reset the password
	*/
	public function reset(NewPasswordRequest $request,$u){
		$username = base64_decode(base64_decode($u));
		$user = User::where('username',$username)->first();
		$user->password = bcrypt(Input::get('password'));

		if($user->save()){
			Auth::loginUsingId($user->id, true);

			$notification_data = new AuditTrailEvent(
				['input' => 
					[
						'user_id'=> Auth::user()->id,
						'action'=>"login",
						"status"=>"success",
						'description'=>"User successfully reset the password and login."
					]
				]
			);
			Event::fire('audit_trail', $notification_data);

			Session::flash('notification-status','success');
			Session::flash('notification-title',"It's nice to be back");
			Session::flash('notification-msg',"Welcome {$this->auth->user()->fname} {$this->auth->user()->lname}!");
			return redirect()->route('frontend.index');
		}
	}

	public function courses(){
		$courses = Course::where('municipality_id',Input::get('_muni_id'))->get();

		$data['courses'] = $courses;

		return response()->json($data,200);
	}

	public function year_level(){
		$course = YearLevel::where('course_code',Input::get('_course_code'))->first();

		if(!$course){
			$data['year_levels'] = [];
		}else{
			$data['year_levels'] = $course->year_level;
		}

		callback:

		return response()->json($data,200);
	}
}