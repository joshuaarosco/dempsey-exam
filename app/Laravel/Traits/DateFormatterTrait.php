<?php 

namespace App\Laravel\Traits;

use Carbon;

trait DateFormatterTrait{

	/**
	 * Parse date to the specified format
	 *
	 * @param date $time
	 * @param string $format
	 *
	 * @return Date
	 */
	public function date_format($time, $format = "M d, Y @ h:i a") {
		return $time == "0000-00-00 00:00:00" ? "" : date($format,strtotime($time));
	}

	/**
	 * Parse date to the 'date only' format
	 *
	 * @param date $time
	 * @param string $format
	 *
	 * @return Date
	 */
	public function date_only($time) {
		$date = $this->date_format($time, "j M Y");
		if(date('Y',strtotime(Carbon::now())) == date("Y",strtotime($time))){
				$date = date("M d",strtotime($time));
		}
		return $date;

	}

	public function month_year($time){
		return date('M Y',strtotime($time));
	}

	/**
	 * Parse date to the 'time only' format
	 *
	 * @param date $time
	 * @param string $format
	 *
	 * @return Date
	 */
	public function time_only($time) {
		return $this->date_format($time, "G:i A");
	}

	/**
	 * Parse date to the standard sql date format
	 *
	 * @param date $time
	 * @param string $format
	 *
	 * @return Date
	 */
	public function date_db($time){
		if(env('DB_CONNECTION','mysql') == "sqlsrv"){
			return date(env('DATEDBSQL_FORMAT','m/d/Y'),strtotime($time));
		}else{
			return date(env('DATEDB_FORMAT','Y-m-d'),strtotime($time));
		}
	}


	/**
	 * Parse date to the standard sql datetime format
	 *
	 * @param date $time
	 * @param string $format
	 *
	 * @return date
	 */
	public function datetime_db($time) {
		if(env('DB_CONNECTION','mysql') == "sqlsrv"){

			return date('m/d/Y H:i:s',strtotime($time));
		}else{
			
			return date('Y-m-d H:i:s',strtotime($time));
		}
	}

	/**
	 * Parse date to a greeting
	 *
	 * @param date $time
	 *
	 * @return string
	 */
	public function greet($time = NULL) {
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

	/**
	 * Shows time passed
	 *
	 * @param date $time
	 *
	 * @return string
	 */
	public function time_passed($time){
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
		/*$date = Carbon::parse($time);

    	if($date->format("Y-m-d") == Carbon::now()->format("Y-m-d")) {
    		return "Today " . $date->format("h:i a");
    	} elseif ($date->between(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek())) {
    		return $date->format("D h:i a");
    	} elseif ($date->format("Y") == Carbon::now()->format("Y")) {
    		return $date->format("d/M h:i a");
    	} else {
    		return $date->format("d/M Y h:i a");
    	}*/

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

	/**
	 * Beautify a model attribute
	 *
	 * @param string $key
	 * @param string $format
	 *
	 * @return string
	 */
	public function pretty_date_field($key, $format = "j M Y @H:i a"){
		return $this->date_format($this->{$key}, $format);
	}

	/**
	 * Formats created_at column
	 *
	 * @param date $time
	 *
	 * @return string
	 */
	public function added_on($key = "created_at", $format = "j M Y @H:i a"){
		return $this->pretty_date_field($key, $format);
	}

	/**
	 * Formats updated_at column
	 *
	 * @param date $time
	 *
	 * @return string
	 */
	public function last_modified($key = "updated_at", $format = "j M Y @H:i a"){
		return $this->pretty_date_field($key, $format);
	}

}