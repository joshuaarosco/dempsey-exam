<?php 

namespace App\Laravel\Controllers\Backoffice;

/*
*
* Models used for this controller
*/
use App\Laravel\Models\User;

/*
*
* Requests used for validating inputs
*/


/*
*
* Classes used for this controller
*/
use Helper, Carbon, Session, Str, DB, Input;

class DashboardController extends Controller{

	/*
	*
	* @var Array $data
	*/
	protected $data;

	public function __construct () {
		$this->data = [];
		parent::__construct();
		array_merge($this->data, parent::get_data());
	}

	public function index () {
		return view('backoffice.dashboard',$this->data);
	}

	public function icons () {
		return view('backoffice.icons',$this->data);
	}
}