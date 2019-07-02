<?php 

namespace App\Laravel\Middleware\Api;

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
                        'action'=>"api access",
                        "status"=>"failed",
                        'description'=>"An unauthorized IP address trying to access the internal api."
                    ]
                ]
            );
            Event::fire('audit_trail', $notification_data);
            $this->response['msg'] = "Unauthorized Access";
            $this->response['status_code'] = "UNAUTHORIZED";
            $this->response['status'] = FALSE;
            $this->response_code = 400;

            return response()->json($this->response,$this->response_code);
        }

        return $next($request);
    }
}