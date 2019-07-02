<?php

namespace App\Laravel\Services;

use App\Laravel\Models\User;
use App\Laravel\Models\Attendance;
use App\Laravel\Models\Cms\Service;
use App\Laravel\Models\ResponseMessage;
use App\Laravel\Models\Cms\SocialLink;
use App\Laravel\Models\ProductDb\Review;
use App\Laravel\Models\ProductDb\Product;
use App\Laravel\Models\ProductDb\Category;
use App\Laravel\Models\ProductDb\ProductImage;
use App\Laravel\Models\ProductDb\ShoppingCart;
use App\Laravel\Models\ReferAndTransactionDb\Thread;
use App\Laravel\Models\ReferAndTransactionDb\Notification;

use Carbon, Str, Route, Auth;
use Illuminate\Support\Facades\Cache;

class Helper {

	public static function date_format($time,$format = "M d, Y @ h:i a") {
		return $time == "0000-00-00 00:00:00" ? "" : date($format,strtotime($time));
	}

	public static function time_passed($time){
		// $current_date = Carbon::now();
		// $secsago = strtotime($current_date) - strtotime($time);
		// $nice_date = 1;
		// if ($secsago < 60):
		// 	if($secsago < 30){ return "just now";}
		//     $period = $secsago == 1 ? '1 sec'     : $secsago . ' sec';
		// elseif ($secsago < 3600) :
		//     $period    =   floor($secsago/60);
		//     $period    =   $period == 1 ? '1 min' : $period . ' min';
		// elseif ($secsago < 86400):
		//     $period    =   floor($secsago/3600);
		//     $period    =   $period == 1 ? '1 hr'   : $period . ' hr';
		// elseif ($secsago < 432000):
		//     $period    =   floor($secsago/86400);
		//     $period    =   $period == 1 ? '1 day'    : $period . ' days';
		// else:
		//    $nice_date = 0;
		//    $period = date("M d, Y",strtotime($time));
		//    if(date('Y',strtotime(Carbon::now())) == date("Y",strtotime($time))){
		//    		$period = date("M d",strtotime($time));
		//    }
		// endif;
		// if($nice_date == 1)
		// 	return $period." ago";
		// else
		// 	return $period;
		$date = Carbon::parse($time);

    	if($date->format("Y-m-d") == Carbon::now()->format("Y-m-d")) {
    		return /*"Today " . */$date->format("h:i a");
    	} elseif ($date->between(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek())) {
    		// return $date->format("D h:i a");
    		return $date->format("D")." at ".$date->format("h:i a");
    	} elseif ($date->format("Y") == Carbon::now()->format("Y")) {
    		return $date->format("d/M")." at ".$date->format("h:i a");
    	} else {
    		return $date->format("d/M Y")." at ".$date->format("h:i a");
    	}
	}

	public static function month_year($time){
		return date('M Y',strtotime($time));
	}

	public static function date_db($time){
		if(env('DB_CONNECTION','mysql') == "sqlsrv"){
			return date(env('DATEDBSQL_FORMAT','m/d/Y'),strtotime($time));
		}else{
			return date(env('DATEDB_FORMAT','Y-m-d'),strtotime($time));
		}
	}

	public static function datetime_db($time){
		if(env('DB_CONNECTION','mysql') == "sqlsrv"){
			return date(env('DATEDBSQL_FORMAT','m/d/Y H:i:s'),strtotime($time));
		}else{
			return date(env('DATEDB_FORMAT','Y-m-d H:i:s'),strtotime($time));
		}
	}

	public static function create_filename($ext) {
		return str_random(20). date("mdYhs") . "." . $ext;
	}

	public static function create_username($name, $counter = 0) {
		return $counter > 0 ? substr(Str::slug($name,""), 0, 19) . ++$counter : substr(Str::slug($name,""), 0, 20);
	}

	public static function str_contract($str) {
		return in_array(substr($str, -1), ['s', "S"]) ? $str . "'" : $str . "'s";
	}

	public static function key_prefix($prefix, array $array = []) {
		foreach($array as $k=>$v){
            $array[$prefix.$k] = $v;
            unset($array[$k]);
        }
        return $array; 
	}

	public static function mask_urls($str, $replace = "{link}") {
		$pattern = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
		return preg_replace($pattern, $replace, $str);
	}

	public static function clean_url($url) {


		// multiple /// messes up parse_url, replace 2+ with 2
		$url = preg_replace('/(\/{2,})/','//',$url);

		$parse_url = parse_url($url);

		if(empty($parse_url["scheme"])) {
		    $parse_url["scheme"] = "http";
		}
		if(empty($parse_url["host"]) && !empty($parse_url["path"])) {
		    // Strip slash from the beginning of path
		    $parse_url["host"] = ltrim($parse_url["path"], '\/');
		    $parse_url["path"] = "";
		}   

		$return_url = "";

		// Check if scheme is correct
		if(!in_array($parse_url["scheme"], array("http", "https", "gopher"))) {
		    $return_url .= 'http'.'://';
		} else {
		    $return_url .= $parse_url["scheme"].'://';
		}

		// Check if the right amount of "www" is set.
		$explode_host = explode(".", $parse_url["host"]);

		// Remove empty entries
		$explode_host = array_filter($explode_host);
		// And reassign indexes
		$explode_host = array_values($explode_host);

		// Contains subdomain
		if(count($explode_host) > 2) {
		    // Check if subdomain only contains the letter w(then not any other subdomain).
		    if(substr_count($explode_host[0], 'w') == strlen($explode_host[0])) {
		        // Replace with "www" to avoid "ww" or "wwww", etc.
		        $explode_host[0] = "www";

		    }
		}

		$return_url .= implode(".",$explode_host);

		if(!empty($parse_url["port"])) {
		    $return_url .= ":".$parse_url["port"];
		}
		if(!empty($parse_url["path"])) {
		    $return_url .= $parse_url["path"];
		}
		if(!empty($parse_url["query"])) {
		    $return_url .= '?'.$parse_url["query"];
		}
		if(!empty($parse_url["fragment"])) {
		    $return_url .= '#'.$parse_url["fragment"];
		}

		return $return_url;
	}

	// public static function get_response_message($code, array $vars = []) {
	// 	$response = "";
	// 	$response_message = Cache::remember($code . implode(".", $vars), 1440, function() use($code) {
	// 		return ResponseMessage::where('code', $code)->first();
	// 	});

	// 	if($response_message) {
	// 		$response = $response_message->content;
	// 		foreach ($vars as $key => $value) {
	// 			$response = str_replace('{'.strtolower($key).'}', $value, $response);
	// 		}
	// 	}
	// 	return $response;
	// }

	public static function is_active(array $routes, $class = "active") {
		return  in_array(Route::currentRouteName(), $routes) ? $class : NULL;
	}

	public static function type_badge($col) {
		$status = Str::lower($col);
		switch ($status) {
			case 'sales_agent': $badge = "<span class='tag tag-default tag-info tag-default'>" . Str::upper(str_replace("_", " ", $col)) . "</span>"; break;
			default: $badge = "<span class='tag tag-default tag-primary tag-default'>" . Str::upper(str_replace("_", " ", $status)) . "</span>"; break;
		}

		return $badge;
	}

	public static function status_badge($col) {
		$status = Str::lower($col);
		switch ($status) {
			case 'no': $badge = "<span class='label label-block label-striped border-left-grey'>" . Str::upper(str_replace("_", " ", "For Approval")) . "</span>"; break;
			case 'yes': $badge = "<span class='label label-block label-striped border-left-success'>" . Str::upper(str_replace("_", " ", "Approved")) . "</span>"; break;
			case 'active': $badge = "<span class='notice notice-blue label label-success'>" . Str::upper(str_replace("_", " ", $status)) . "</span>"; break;
			case 'expired': $badge = "<span class='notice notice-danger label label-danger'>" . Str::upper(str_replace("_", " ", $status)) . "</span>";  break;
			case 'paid': $badge = "<span class='notice notice-blue label label-success'>" . Str::upper(str_replace("_", " ", $status)) . "</span>"; break;
			case 'pending': $badge = "<span class='notice notice-warning label label-warning'>" . Str::upper(str_replace("_", " ", $status)) . "</span>";  break;
			case 'overdue': $badge = "<span class='notice notice-danger label label-danger'>" . Str::upper(str_replace("_", " ", $status)) . "</span>"; break;
			case 'open': $badge = "<span class='notice notice-blue label label-info'>" . Str::upper(str_replace("_", " ", $status)) . "</span>"; break;
			case 'closed': $badge = "<span class='notice notice-default label label-default'>" . Str::upper(str_replace("_", " ", $status)) . "</span>"; break;
			case 'low': $badge = "<span class='notice notice-blue label label-sold'>" . Str::upper(str_replace("_", " ", $status)) . "</span>"; break;
			case 'medium': $badge = "<span class='notice notice-warning label label-warning'>" . Str::upper(str_replace("_", " ", $status)) . "</span>"; break;
			case 'high': $badge = "<span class='notice notice-danger label label-danger'>" . Str::upper(str_replace("_", " ", $status)) . "</span>"; break;
			default: $badge = "<span class='label label-block label-striped border-left-default'>" . Str::upper(str_replace("_", " ", $status)) . "</span>"; break;
		}

		return $badge;
	}

	public static function formatSizeUnits($bytes)
	{
		if ($bytes >= 1073741824)
		{
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		}
		elseif ($bytes >= 1048576)
		{
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		}
		elseif ($bytes >= 1024)
		{
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		}
		elseif ($bytes > 1)
		{
			$bytes = $bytes . ' bytes';
		}
		elseif ($bytes == 1)
		{
			$bytes = $bytes . ' byte';
		}
		else
		{
			$bytes = '0 bytes';
		}

		return $bytes;
	}

	public static function product_image($id,$type){
		switch ($type) {
			case 'first-img':
				$product_image = ProductImage::where('product_id',$id)->orderBy('created_at')->first();
				break;
			
			default:
				$product_image = ProductImage::where('product_id',$id)->skip(1)->first();
				break;
		}
		
		if($product_image)
			return $product_image->directory.'/'.$product_image->filename;
		return 'frontend/img/default-product.jpg';
	}

	public static function user_information($refer_id,$type){
		$user = User::where('refer_id',$refer_id)->first();
		switch ($type) {
			case 'page':
				return route('frontend.account.profile',$user->username);
				break;
			
			default:
				return route('frontend.account.profile',$user->username);
				break;
		}
	}

	public static function findUserName($user_id){
   		$user = User::where('refer_id',$user_id)->first();

		if(!$user){
			return "User Not Found.";
		}else{
			return User::where('refer_id',$user_id)->first()->fname.' '.User::where('refer_id',$user_id)->first()->lname;
		}
	}

	public static function findUserImage($user_id){
		$user = User::where('refer_id',$user_id)->first();
		if(!$user){
			return asset('backoffice/face0.jpg');
		}

		if($user->directory == ''){
			$image = asset('backoffice/face0.jpg');
		}else{
			$image = asset($user->directory.'/'.$user->filename);
		}
		return $image;
	} 

	public static function findUserUsername($user_id){
		$user = User::where('refer_id',$user_id)->first();

		if(!$user){
			return "User not found.";
		}else{
			return User::where('refer_id',$user_id)->first()->username;
		}
	}

	public static function getCategoryName($category_id){
		$category = Category::find($category_id);
		return $category->category_name;
	}

	public static function getParentCategory($category_id){
		$category = Category::find($category_id);
		if(!$category){
			return '';
		}else{
			switch ($category->type ) {
				case 'specific_category':

				$category_ids = [];

				array_push($category_ids,$category->id);
				array_push($category_ids,$category->parent_category_id);
				Helper::getParentCategory($category->parent_category_id);

				$category_ids = array_merge($category_ids,Helper::getParentCategory($category->parent_category_id));

				return array_unique($category_ids);
				

				break;

				case 'sub_category':

				$category_ids = [];

				array_push($category_ids,$category->id);
				array_push($category_ids,$category->parent_category_id);

				return $category_ids;

				break; 

				default:
				
				return [$category->id];

				break;
			}
		}
	}

	public static function calculateRating($id,$get,$type){
		$reviews = Review::where('reviews_id',$id)->where('type',$type)->get();

		if($type == 'user'){
			$product_ids = Product::where('user_id',$id)->pluck('id');
			$reviews = Review::whereIn('reviews_id',$product_ids)->where('type','product')->get();
		}

		if($type == 'user_review_count'){
			$product_ids = Product::where('user_id',$id)->pluck('id');
			$review_count = Review::whereIn('reviews_id',$product_ids)->where('type','product')->count();

			return $review_count;
		}

		if($reviews->count() == 0){
			if($type == 'user'){
				return 'N/A';
			}elseif($get == 'reviews'){
				return 0;
			}else{
				return 'No ratings yet';
			}
		}

		$rating_5 = [];
		$rating_4 = [];
		$rating_3 = [];
		$rating_2 = [];
		$rating_1 = [];
		foreach ($reviews as $key => $value) {
			switch ($value->rating) {
				case 1:
					array_push($rating_1,$value->rating);
					break;
				case 2:
					array_push($rating_2,$value->rating);
					break;
				case 3:
					array_push($rating_3,$value->rating);
					break;
				case 4:
					array_push($rating_4,$value->rating);
					break;
				case 5:
					array_push($rating_5,$value->rating);
					break;
				
				default:
					array_push($rating_5,$value->rating);
					break;
			}
		}
		$num2 = (count($rating_5)+count($rating_4)+count($rating_3)+count($rating_2)+count($rating_1));
		if($num2 == 0){
			return 0;
		}
		$rating = (5*count($rating_5) + 4*count($rating_4) + 3*count($rating_3) + 2*count($rating_2) + 1*count($rating_1)) / $num2;

		if($get == "rating" OR $get == "reviews"){
			return number_format($rating,1);
		}elseif($get == "rate_out_of_5"){
			$stars = floor($rating);
			$decimal = is_numeric( $rating ) && floor( $rating ) != $rating;
			$counter = 5;
			if($stars>0){
				echo "<span title='".number_format($rating,1)."' data-toggle='tooltip' class='mr-5'>";
				foreach(range(1,$stars) as $value){

					$counter -= 1;
					echo '<i class="fa fa-star" style="color: #F9BA48!important;"></i>';
				}
				if($decimal){

					$counter -= 1;

					echo '<i class="fa fa-star-half-o" style="color: #F9BA48!important;"></i>';
				}

				if($counter>0){
					foreach(range(1,$counter) as $value){
						echo '<i class="fa fa-star on-color" style="color: #cecece!important;"></i>';
					}
				}
				echo "</span> <span class='text-green'> ".number_format($rating,1)." out of 5 ( ".$reviews->count()." Rating ) </span>";
			}
		}else{
			$stars = floor($rating);
			$decimal = is_numeric( $rating ) && floor( $rating ) != $rating;
			$counter = 5;
			if($stars>0){
				echo "<span title='".number_format($rating,1)."' data-toggle='tooltip'>";
				foreach(range(1,$stars) as $value){

					$counter -= 1;
					echo '<i class="fa fa-star" style="color: #F9BA48!important;"></i>';
				}
				if($decimal){

					$counter -= 1;

					echo '<i class="fa fa-star-half-o" style="color: #F9BA48!important;"></i>';
				}

				if($counter>0){
					foreach(range(1,$counter) as $value){
						echo '<i class="fa fa-star on-color" style="color: #cecece!important;"></i>';
					}
				}
				echo "</span> ( ".$reviews->count()." )";
			}
		}
	}

	public static function status_label($status){
		switch ($status) {
			case 'pending':
				return "warning";
				break;

			case 'cancelled':
				return "danger";
				break;

			case 'approved':
				return "success";
				break;
			
			default:
				return "default";
				break;
		}
	}

	public static function get_sub_categories($id){
		$categories = Category::where('type','sub_category')
		->where('parent_category_id',$id)
		->pluck('category_name')
		->toArray();

		return $categories;
	}

	public static function get_refer_id($username){
		$user = User::where('username',$username)->first();

		return $user;
	}

	public static function get_chat_name($participants_id,$refer_id){
		$exploded = explode(':',$participants_id);
		$key = array_search($refer_id,$exploded);
		if($key == 0){
			$key = 1;
		}else{
			$key = 0;
		}

		return Helper::findUserName($exploded[$key]);
	}

	public static function get_chat_image($participants_id,$refer_id){
		$exploded = explode(':',$participants_id);
		$key = array_search($refer_id,$exploded);
		if($key == 0){
			$key = 1;
		}else{
			$key = 0;
		}

		return Helper::findUserImage($exploded[$key]);
	}

	public static function get_chatmate_refer_id($thread_id,$refer_id){
		$thread = Thread::where('id',$thread_id)->first();
		$exploded = explode(':',$thread->participants_id);
		$key = array_search($refer_id,$exploded);
		if($key == 0){
			$key = 1;
		}else{
			$key = 0;
		}

		return $exploded[$key];
	}

	public static function cart($type){
		if(Auth::check()){
			$cart = ShoppingCart::where('user_id',Auth::user()->id)->where('is_selected','yes')->AvailableProductOnly()->get();

			$sub_total = [];
			foreach ($cart as $key => $value) {
				array_push($sub_total,($value->price * $value->qty));
			}
			$sub_total = array_sum($sub_total);

			switch ($type) {
				case 'sub_total':
				return $sub_total;
				break;

				default:
				return $cart;
				break;
			}
		}else{
			return null;
		}
	}

	public static function notifications(){
		if(Auth::check()){
			$notifications = Notification::where('user_id',Auth::user()->id)->orderBy('created_at','DESC')->get();

			return $notifications;
		}else{
			return 0;
		}
	}

	public static function attendance_checker(){
		$today = Helper::date_format(Carbon::now(),'Y-m-d');
		$attendance = Attendance::where('user_id',Auth::user()->id)->whereDate('time_in','>=',$today)->first();
		return $attendance;
	}

	public static function unread_messages(){
		// Auth::
	}

	public static function findName($username){
		$user = User::where('username',$username)->first();

		return $user->shop_name?:$user->fname.' '.$user->lname;
	}

	public static function greet($time = NULL) {
		if(!$time) $time = Carbon::now();
		$hour = date("G",strtotime($time));

		if($hour < 5) {
			$greeting = "You woke up early";
		}elseif($hour < 10){
			$greeting = "Good morning";
		}elseif($hour <= 12){
			$greeting = "It's almost lunch";
		}elseif($hour < 18){
			$greeting = "Good afternoon";
		}elseif($hour <= 22){
			$greeting = "Good evening";
		}else{
			$greeting = "You must be working really hard";
		}

		return $greeting;
	}

	public static function social_links(){
		return SocialLink::all();
	}

	public static function it_services(){
		return Service::where('type','it')->get();
	}

	public static function cs_services(){
		return Service::where('type','cs')->get();
	}

	public static function m_services(){
		return Service::where('type','m')->get();
	}

}