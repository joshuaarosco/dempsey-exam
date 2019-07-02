<?php

namespace App\Laravel\Services;

use App\Laravel\Models\ResponseMessage;
use Illuminate\Support\Facades\Cache;
use Carbon, Str,Auth;

class AuthHelper {

	public static function info($str){
		$user = Auth::user();
		return $user = Auth::user() ? $user->$str : '';
	}

}