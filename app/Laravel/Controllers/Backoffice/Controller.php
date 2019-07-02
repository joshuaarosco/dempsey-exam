<?php 

namespace App\Laravel\Controllers\Backoffice;


use Illuminate\Support\Collection;
use App\Http\Controllers\Controller as MainController;
use Auth, Session,Carbon, Helper,Route,DNS2D;

class Controller extends MainController{

	protected $data;

	public function __construct(){
		self::set_user_info();
		self::set_date_today();
		self::set_current_route();
		self::set_default_page_title();
	}

	public function set_user_info(){
		$this->data['auth'] = Auth::user();
	}
	
	public function set_current_route(){
		 $this->data['current_route'] = Route::currentRouteName();
	}

	public function get_user_info(){
		return $this->data['auth'];
	}

	public function set_default_page_title(){
		$this->data['page_title']= "Dashboard";
		$this->data['page_Description']= "Default Page Description";
	}
	
	public function get_data(){
		return $this->data;
	}

	public function set_date_today(){
		$this->data['date_today'] = Helper::date_db(Carbon::now());
	}
}