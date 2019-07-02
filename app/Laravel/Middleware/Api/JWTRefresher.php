<?php

namespace App\Laravel\Middleware\Api;

use Helper;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class JWTRefresher extends BaseMiddleware
{

    protected $format;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $this->format = $request->format;

        try {
            $jwtauth = $this->auth->setRequest($request)->parseToken();
            $user = $jwtauth->authenticate();
            $new_token = $jwtauth->refresh();
        } catch (TokenExpiredException $e) {
            return $this->respond('tymon.jwt.expired', 'token_expired', $e->getStatusCode(), [$e]);
        } catch (JWTException $e) {
            return $this->respond('tymon.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
        }

        $request->merge(['new_token' => $new_token, 'user' => $user]);
        return $next($request);
    }

    /**
     * Fire event and return the response.
     *
     * @param  string   $event
     * @param  string   $error
     * @param  int  $status
     * @param  array    $payload
     * @return mixed
     */
    protected function respond($event, $error, $status, $payload = [])
    {
        $response = array();

        switch ($error) {
            case 'token_not_provided' :
                $response = [
                    'msg' => Helper::get_response_message("TOKEN_NOT_PROVIDED"),
                    'status' => FALSE,
                    'status_code' => "TOKEN_NOT_PROVIDED",
                    'hint' => "You can obtain a token in a successful login/register request.",
                ];
            break;
            case 'token_expired' :
                $response = [
                    'msg' => Helper::get_response_message("TOKEN_EXPIRED"),
                    'status' => FALSE,
                    'status_code' => "TOKEN_EXPIRED",
                    'hint' => "You must try refreshing your token. If this error still occurs, you must re-login.",
                ];
            break;
            case 'token_invalid' :
                $response = [
                    'msg' => Helper::get_response_message("TOKEN_INVALID"),
                    'status' => FALSE,
                    'status_code' => "TOKEN_INVALID",
                    'hint' => "You can obtain a token in a successful login/register request.",
                ];
            break;
            case 'user_not_found' :
                $response = [
                    'msg' => Helper::get_response_message("INVALID_AUTH_USER"),
                    'status' => FALSE,
                    'status_code' => "INVALID_AUTH_USER",
                ];
            break;
        }

        $successful = $this->events->fire($event, $payload, true);

        if($successful) {
            return $successful;
        }

        switch ($this->format) {
            case 'json':
                return response()->json($response, 401);
            break;
            case 'xml':
                return response()->xml($response, 401);
            break;
        }
    }
}