<?php 

namespace App\Laravel\Controllers\Frontend;

/*
*
* Models used for this controller
*/
use Pusher\Pusher;
use App\Laravel\Models\User;
use App\Laravel\Models\Cms\BLog;
use App\Laravel\Models\Subscriber;
use App\Laravel\Models\Cms\Service;
use App\Laravel\Models\Cms\Portfolio;
use App\Laravel\Models\ContactInquiry;
use App\Laravel\Models\Cms\PortfolioImage;

/*
*
* Requests used for validating inputs
*/

use Illuminate\Http\Request;
use App\Laravel\Events\SendEmail;
use App\Laravel\Events\PusherEvent;
use App\Laravel\Requests\Frontend\SubscribeRequest;
use App\Laravel\Requests\Frontend\ContactInquiryRequest;

/*
*
* Classes used for this controller
*/
use Helper, Carbon, Session, Str, DB, Input, Event, Auth, Redirect,URL;

class PageController extends Controller{

	/*
	*
	* @var Array $data
	*/
	protected $data;

	public function __construct () {
		$this->data = [];
		parent::__construct();
		array_merge($this->data, parent::get_data());
		$this->data['types'] =  ['it' => "Information Technology" , 'cs' => "Chimes Solution", 'm' => "Marketing"];
	}

	/*
	This function is used to view the homepage
	*/
	public function index () {
		return redirect()->route('backoffice.dashboard');
	}
}