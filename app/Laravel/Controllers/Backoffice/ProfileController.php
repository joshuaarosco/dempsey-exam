<?php namespace App\Laravel\Controllers\Backoffice;

/**
*
* Models used for this controller
*/
use App\Laravel\Models\User;
use App\Laravel\Models\ActivityLog;

/**
*
* Requests used for validating inputs
*/
use App\Laravel\Requests\Backoffice\ProfileRequest;
use App\Laravel\Requests\Backoffice\PasswordRequest;

/**
*
* Classes used for this controller
*/
// use App\Http\Requests\Request;
use Helper, Carbon, Session, Str,File,ImageUploader, AuthHelper, Auth, Request;

class ProfileController extends Controller{

	/**
	*
	* @var Array $data
	*/
	protected $data;

	public function __construct () {
		$this->data = [];
		parent::__construct();
		array_merge($this->data, parent::get_data());
		$this->data['page_title'] = "Account Setting";
		$this->data['page_description'] = "This is the general information about ".$this->data['page_title'].".";

		$this->data['form_positions'] = ["" => "Choose Position","center" => "Center","left" => "Left" , "right" => "Right"];
	}

	public function setting () {
		return view('backoffice.auth.setting',$this->data);
	}

	public function update_setting (ProfileRequest $request) {
		try {
			$user = User::find(Auth::user()->id);
			$user->fill($request->only('fname','lname', 'username', 'email', 'contact_number'));
			$user->username = strtolower(str_replace(" ", "_", $request->get('username')));

			if($request->hasFile('file')){
				$upload = ImageUploader::upload($request->file,'storage/avatar/'.AuthHelper::info('id'));
				$user->directory = $upload["directory"];
				$user->filename = $upload["filename"];
			}

			if($user->save()) {

				Session::flash('notification-status','success');
				Session::flash('notification-msg',"User profile has been updated.");
				return redirect()->route('backoffice.profile.settings');
			}

			Session::flash('notification-status','success');
			Session::flash('notification-msg',"User profile has been updated.");
			return redirect()->route('backoffice.profile.settings');

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

	public function update_password(PasswordRequest $request) {
		$user = Auth::user();
		$user->password = bcrypt($request->get('new_password'));
		if($user->save()){

			Session::flash('notification-status','success');
			Session::flash('notification-msg','Password was successfully updated.');
		}
		
		return redirect()->route('backoffice.profile.settings');
	}

}