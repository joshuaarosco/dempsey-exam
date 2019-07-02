<?php 
namespace App\Laravel\Controllers\Backoffice;

/**
*
* Models used for this controller
*/
use App\Laravel\Models\User;

/**
*
* Requests used for validating inputs
*/
use App\Laravel\Requests\Backoffice\UserRequest;

/**
*
* Classes used for this controller
*/
use Helper, Carbon, Session, Str;

class UserController extends Controller{

	/**
	*
	* @var Array $data
	*/
	protected $data;

	public function __construct () {
		$this->data = [];
		parent::__construct();
		array_merge($this->data, parent::get_data());
		$this->data['statuses'] = ['' => "Choose status", 'draft' => "Draft", 'published' => "Published"];
		$this->data['user_types'] = ['' => "Choose Account Type",'user' => "User", 'super_user' => "Super Admin",'admin' => "Admin",'finance' => "Finance", 'editor' => "Website Editor"];
		$this->data['def_stats'] = ['' => "Choose Status",'yes' => "Yes", 'no' => "no"];
	}

	public function index () {
		$this->data['users'] = User::orderBy('created_at',"DESC")->get();
		return view('backoffice.users.index',$this->data);
	}

	public function create () {
		return view('backoffice.users.create',$this->data);
	}

	public function store (UserRequest $request) {
		try {
			$new_user = new User;
			$new_user->fill($request->all());
			$new_user->type = "user";
			$new_user->email = Str::lower($request->get('email'));
			$new_user->password = bcrypt($request->get('password'));

			if($new_user->save()) {
				Session::flash('notification-status','success');
				Session::flash('notification-msg',"New user has been added.");
				return redirect()->route('backoffice.users.index');
			}

			Session::flash('notification-status','failed');
			Session::flash('notification-msg','Something went wrong.');

			return redirect()->back();
		} catch (Exception $e) {
			Session::flash('notification-status','failed');
			Session::flash('notification-msg',$e->getMessage());
			return redirect()->back();
		}
	}

	public function edit ($id = NULL) {
		$user = User::find($id);

		if (!$user) {
			Session::flash('notification-status',"failed");
			Session::flash('notification-msg',"Record not found.");
			return redirect()->route('backoffice.users.index');
		}

		$this->data['user'] = $user;
		return view('backoffice.users.edit',$this->data);
	}

	public function update (UserRequest $request, $id = NULL) {
		try {
			$user = User::find($id);

			if (!$user) {
				Session::flash('notification-status',"failed");
				Session::flash('notification-msg',"Record not found.");
				return redirect()->route('backoffice.users.index');
			}

			$user->fill($request->except('password'));

			if($request->has('password')){
				$user->password = bcrypt($request->get('password'));
			}


			if($user->save()) {
				Session::flash('notification-status','success');
				Session::flash('notification-msg',"A user has been updated.");
				return redirect()->route('backoffice.users.index');
			}

			Session::flash('notification-status','failed');
			Session::flash('notification-msg','Something went wrong.');

		} catch (Exception $e) {
			Session::flash('notification-status','failed');
			Session::flash('notification-msg',$e->getMessage());
			return redirect()->back();
		}
	}

	public function destroy ($id = NULL) {
		try {
			$user = User::find($id);

			if (!$user) {
				Session::flash('notification-status',"failed");
				Session::flash('notification-msg',"Record not found.");
				return redirect()->route('backoffice.users.index');
			}

			$user->email .= "-deleted-" . Helper::date_format(Carbon::now(),"YmdHis");
			$user->username .= "-deleted-" . Helper::date_format(Carbon::now(),"YmdHis");

			if($user->delete()) {
				Session::flash('notification-status','success');
				Session::flash('notification-msg',"A user has been deleted.");
				return redirect()->route('backoffice.users.index');
			}

			Session::flash('notification-status','failed');
			Session::flash('notification-msg','Something went wrong.');

		} catch (Exception $e) {
			Session::flash('notification-status','failed');
			Session::flash('notification-msg',$e->getMessage());
			return redirect()->back();
		}
	}

}