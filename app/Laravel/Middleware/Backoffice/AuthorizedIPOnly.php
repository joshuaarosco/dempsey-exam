<?php 

namespace App\Laravel\Middleware\Backoffice;

use Closure, Session, Event, Auth;
use Illuminate\Contracts\Auth\Guard;
use App\Laravel\Events\AuditTrailEvent;
use App\Laravel\Models\Security\AuthorizedIpAddress;

class AuthorizedIPOnly {

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
        $authorized_ip = AuthorizedIpAddress::pluck('ip_address')->toArray();
        if (!in_array(\Request::ip(), $authorized_ip)) {

            $notification_data = new AuditTrailEvent(
                ['input' => 
                    [
                        'user_id'=> Auth::check()?Auth::user()->id:0,
                        'action'=>"access",
                        "status"=>"failed",
                        'description'=>"Failed to access the admin panel link."
                    ]
                ]
            );
            Event::fire('audit_trail', $notification_data);

            return redirect()->route('frontend.404');
        }

        return $next($request);
    }

}