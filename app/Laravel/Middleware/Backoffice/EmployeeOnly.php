<?php 

namespace App\Laravel\Middleware\Backoffice;

use Closure, Session, Auth;
use Illuminate\Contracts\Auth\Guard;

class EmployeeOnly {

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
        if (in_array($this->auth->user()->type, ['member','networker'])) {
            Session::flash('notification-status','failed');
            Session::flash('notification-msg',"You don't have enough access to view the requested page.");
            Auth::logout();
            return redirect()->route('backoffice.auth.login');
        }

        return $next($request);
    }

}