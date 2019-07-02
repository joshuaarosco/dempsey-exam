<?php

namespace App\Exceptions;

use Exception, Request, Helper, Session;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {   

        if($exception instanceof TokenMismatchException){
            Session::flash('notification-status','failed');
            Session::flash('notification-msg',"Oops! Seems you couldn't submit form for a long time. Please try again.");
            return redirect($request->fullUrl());
        }

        if ($request->expectsJson() OR Request::segment(1) == "api") {
            $format = $request->route('format');
            
            $response = [
                'msg' => Helper::get_response_message("SERVER_ERROR"),
                'status' => FALSE,
                'status_code' => "SERVER_ERROR",
                'exception' => $exception->getMessage(),
            ];

            $response_code = 500;

            if($exception instanceof RequestException) {
                $response['msg'] = Helper::get_response_message("CURL_ERROR");
                $response['status_code'] = "CURL_ERROR";
                $response_code = 409;
                switch ($format) {
                    case 'json':
                        return response()->json($response, $response_code);
                    break;
                    case 'xml':
                        return response()->xml($response, $response_code);
                    break;
                }
            } else {

                // if($exception instanceof \Illuminate\Validation\ValidationException) goto callback;
                // return response()->json($response, $response_code);
            }

            // callback:
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson() OR $request->segments()[0] == "api") {
            return response()->json(['msg' => "Unauthenticated", 'status' => FALSE, 'status_code' => "UNAUTHENTICATED"], 401);
        }
        return redirect()->guest('login');
    }
}
