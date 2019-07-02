<?php 

namespace App\Laravel\Middleware\Api;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class ApiTokenizer {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if($request->get('api_token') != env("APP_KEY")){
			$response = array(
					"msg" => "Invalid Token. Unable to process request",
					"status" => FALSE,
					'status_code' => "INVALID_TOKEN"
				);
			$response_code = 401;

			return response($response, $response_code);
		}

		return $next($request);
	}

}