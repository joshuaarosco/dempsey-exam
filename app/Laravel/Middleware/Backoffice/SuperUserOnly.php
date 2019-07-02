<?php 

namespace App\Laravel\Middleware\Backoffice;

use Closure, Session;
use Illuminate\Contracts\Auth\Guard;

class SuperUserOnly {

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
        if ($this->auth->user()->type != 'super_user') {
            Session::flash('notification-status','failed');
            Session::flash('notification-msg',"You don't have enough access to view the requested page.");
            return redirect()->route('backoffice.dashboard');
        }

        return $next($request);
    }

}