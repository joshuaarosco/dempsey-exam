<?php 
namespace App\Laravel\Controllers\Backoffice;

/**
*
* Models used for this controller
*/
use App\Laravel\Models\User;
use App\Laravel\Models\Payment;

/**
*
* Requests used for validating inputs
*/
use App\Laravel\Requests\Backoffice\EmployeeRequest;
use App\Laravel\Requests\Backoffice\EditEmployeeRequest;

/**
*
* Classes used for this controller
*/
use App\Http\Requests\Request;
use App\Laravel\Events\SendEmail;
use Input, Helper, Carbon, Session, Str, File, Image, Event, ImageUploader;

class EmployeeController extends Controller{

	/**
	*
	* @var Array $data
	*/
	protected $data;

	public function __construct () {
		parent::__construct();
		$view = Input::get('view','table');
		array_merge($this->data, parent::get_data());
		$this->data['page_title'] = "Employee";
		$this->data['page_description'] = "This is the general information about ".$this->data['page_title'].".";
		$this->data['route_file'] = "employees";
	}

	/*
	This function is used to view the employee list
	*/
	public function index () {
		$this->data['employees'] = User::orderBy('created_at',"DESC")
									->where('type','employee')
									->Keyword(Input::has('search')?Input::get('search'):null)
									->paginate(10);
		return view('backoffice.'.$this->data['route_file'].'.index',$this->data);
	}

	/*
	This function is used to store the employee data
	*/
	public function store (EmployeeRequest $request) {
		try {
			$new_employee = new User;
			$new_employee->fill($request->all());
			$new_employee->type = "employee";
			$new_employee->email = Str::lower($request->get('email'));
			$new_employee->password = bcrypt($request->password);
			$new_employee->authentication_code = bcrypt($request->password);

			if(Input::hasFile('file')){
				$upload = ImageUploader::upload($request['file'],'storage/employees');
				$new_employee->path = $upload["path"];
				$new_employee->directory = $upload["directory"];
				$new_employee->filename = $upload["filename"];
			}

			if($new_employee->save()) {
				Session::flash('notification-status','success');
				Session::flash('notification-msg',"New employee has been added.");
				return redirect()->route('backoffice.'.$this->data['route_file'].'.index');
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

	/*
	This function is used to update the employee data
	*/
	public function update (EditEmployeeRequest $request) {
		try {
			$employee = User::find($request->id);
			
			if (!$employee) {
				Session::flash('notification-status',"failed");
				Session::flash('notification-msg',"Record not found.");
				return redirect()->route('backoffice.'.$this->data['route_file'].'.index');
			}

			$employee->fill($request->all());

			if(Input::hasFile('file')){
				$upload = ImageUploader::upload($request['file'],'storage/employees');
				$employee->path = $upload["path"];
				$employee->directory = $upload["directory"];
				$employee->filename = $upload["filename"];
			}

			if($employee->save()) {
				Session::flash('notification-status','success');
				Session::flash('notification-msg',"An employee has been updated.");
				return redirect()->route('backoffice.'.$this->data['route_file'].'.index');
			}

			Session::flash('notification-status','failed');
			Session::flash('notification-msg','Something went wrong.');

		} catch (Exception $e) {
			Session::flash('notification-status','failed');
			Session::flash('notification-msg',$e->getMessage());
			return redirect()->back();
		}
	}

	/*
	This function is used to delete the employee data
	*/
	public function destroy ($id = NULL) {
		try {
			$employee = User::find($id);

			if (!$employee) {
				Session::flash('notification-status',"failed");
				Session::flash('notification-msg',"Record not found.");
				return redirect()->route('backoffice.'.$this->data['route_file'].'.index');
			}

			if (File::exists("{$employee->directory}/{$employee->filename}")){
				File::delete("{$employee->directory}/{$employee->filename}");
			}
			if (File::exists("{$employee->directory}/resized/{$employee->filename}")){
				File::delete("{$employee->directory}/resized/{$employee->filename}");
			}
			if (File::exists("{$employee->directory}/thumbnails/{$employee->filename}")){
				File::delete("{$employee->directory}/thumbnails/{$employee->filename}");
			}

			if($employee->delete()) {
				Session::flash('notification-status','success');
				Session::flash('notification-msg',"An employee has been deleted.");
				return redirect()->route('backoffice.'.$this->data['route_file'].'.index');
			}

			Session::flash('notification-status','failed');
			Session::flash('notification-msg','Something went wrong.');

		} catch (Exception $e) {
			Session::flash('notification-status','failed');
			Session::flash('notification-msg',$e->getMessage());
			return redirect()->back();
		}
	}


	/*
	This function is used to add payment
	*/
	public function add_pay(){
		$new_payment = new Payment;
		$new_payment->fill(Input::all());

		if($new_payment->save()){
			Session::flash('notification-status','success');
			Session::flash('notification-msg','Payment successfully added');
			return redirect()->back();
		}
	}

	/*
	This function is used to view pay history
	*/
	public function pay_history(){
		$history = Payment::where('user_id',Input::get('user_id'))->orderBy('created_at','DESC')->get();

		$data['count'] = $history->count();
		$data['history'] = $history;

		callback:

		return response()->json($data,200);
	}

	public function search(){
		$results = User::orderBy('created_at',"DESC")
									->where('type','employee')
									->Keyword(Input::has('search')?Input::get('search'):null)
									->get();

		if($results->count() > 0){
			$output = [];
			foreach ($results as $key => $info) {
				array_push($output, '
								<tr class="result">
                                    <td></td>
                                    <td>'.($key+1).'</td>
                                    <td><img src="'.asset($info->directory.'/'.$info->filename).'" height="50" width="50"></td>
                                    <td>'.Str::limit($info->fname.' '.$info->lname,30).'</td>
                                    <td>'.Helper::date_format($info->birthdate,'F d, Y').'</td>
                                    <td>'.$info->contact_number.'<br>
                                        <a href="mailto:'.$info->email.'">'.$info->email.'</a>
                                    </td>
                                    <td>'.number_format($info->payment($info->id),2).'</td>
                                    <td>
                                         <div class="dropdown">
                                            <div class="btn-group">
                                            <button class="btn btn-default btn-sm text-dark" type="button" data-toggle="dropdown"><i class="fa fa-cog"></i> Action </button>
                                            <div class="dropdown-menu">
                                              <a class="dropdown-item action-add-payment" href="#" data-user-id="'.$info->id.'" data-toggle="modal" data-target=".add-pay"><i class="fa fa-money"></i> Add Pay</a>
                                              <div class="dropdown-divider"></div>
                                              <a class="dropdown-item action-view-history" href="#" data-user-id="'.$info->id.'" data-toggle="modal" data-target=".pay-history"><i class="fa fa-clock-o"></i> Pay History</a>
                                              <div class="dropdown-divider"></div>
                                              <a class="dropdown-item action-edit" href="#" 
                                              data-toggle="modal"
                                              data-target=".edit"
                                              data-id="'.$info->id.'"
                                              data-fname="'.$info->fname.'" 
                                              data-lname="'.$info->lname.'" 
                                              data-birthdate="'.$info->birthdate.'"
                                              data-email="'.$info->email.'"
                                              data-contact_number="'.$info->contact_number.'"
                                              data-img="'.asset($info->directory.'/'.$info->filename).'">
                                              <i class="fa fa-pencil"></i> Edit</a>
                                              <div class="dropdown-divider"></div>
                                              <a class="dropdown-item action-delete" href="#" data-url="'.route('backoffice.employees.destroy',$info->id).'" data-toggle="modal" data-target=".delete"><i class="fa fa-trash-o"></i> Delete</a>
                                            </div>
                                            </div>
                                      </div>
                                  	</td>
                                </tr>
					');
			}

			$data['results'] = implode('', $output);

		}else{
			$data['results'] = '<tr>
	                                <td colspan="7" class="text-center">
	                                    <small>No search result for "'.Input::get('search').'"</small>
	                                </td>
	                            </tr>';
		}

		callback:

		return response()->json($data,200);
	}

	/**
	*
	*@param App\Http\Requests\RequestRequest $request
	*@param string $request
	*
	*@return array
	*/
	private function __upload(Request $request, $directory = "uploads/employee"){
		$file = $request->file("file");
		$ext = $file->getClientOriginalExtension();

		$path_directory = $directory."/".Helper::date_format(Carbon::now(),"Ymd");
		$resized_directory = $directory."/".Helper::date_format(Carbon::now(),"Ymd")."/resized";
		$thumb_directory = $directory."/".Helper::date_format(Carbon::now(),"Ymd")."/thumbnails";

		if (!File::exists($path_directory)){
			File::makeDirectory($path_directory, $mode = 0777, true, true);
		}

		if (!File::exists($resized_directory)){
			File::makeDirectory($resized_directory, $mode = 0777, true, true);
		}

		if (!File::exists($thumb_directory)){
			File::makeDirectory($thumb_directory, $mode = 0777, true, true);
		}

		$filename = Helper::create_filename($ext);

		$file->move($path_directory, $filename); 
		Image::make("{$path_directory}/{$filename}")->save("{$resized_directory}/{$filename}",90);
		Image::make("{$path_directory}/{$filename}")->resize(250,250)->save("{$thumb_directory}/{$filename}",90);

		return [ "directory" => $path_directory, "filename" => $filename ];
	}

}