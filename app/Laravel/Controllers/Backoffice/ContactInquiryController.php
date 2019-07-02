<?php namespace App\Laravel\Controllers\Backoffice;

/**
*
* Models used for this controller
*/
use App\Laravel\Models\ContactInquiry;

/**
*
* Requests used for validating inputs
*/
use App\Laravel\Requests\Backoffice\AdvertisementRequest;

/**
*
* Classes used for this controller
*/
use App\Http\Requests\Request;
use Input, Helper, Carbon, Session, Str, File, Image, ImageUploader, Excel;

class ContactInquiryController extends Controller{

	/**
	*
	* @var Array $data
	*/
	protected $data;

	public function __construct () {
		parent::__construct();
		$view = Input::get('view','table');
		array_merge($this->data, parent::get_data());
		$this->data['page_title'] = "Contact Inquiry";
		$this->data['page_description'] = "This is the general information about ".$this->data['page_title'].".";
		$this->data['route_file'] = "contact_inquiries";
	}

	public function index () {
		$this->data['datas'] = ContactInquiry::orderBy('created_at',"DESC")->paginate(10);
		return view('backoffice.'.$this->data['route_file'].'.index',$this->data);
	}

	public function download(){
		$this->data['users'] = ContactInquiry::orderBy('created_at',"DESC")->get();

		Excel::create('Subscribers', function($excel) {
			$excel->sheet('Sheet1', function($sheet) {

				$sheet->loadView('excel.users',$this->data);
			});
		})->download('xlsx');
	}
}