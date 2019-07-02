<?php 

namespace App\Laravel\Events;


use App\Laravel\Models\Security\AuditTrail;
use Illuminate\Queue\SerializesModels;
use Mail,Str,Request;
// use App\Constech\Models\GeneralSetting;

class AuditTrailEvent extends Event {

	use SerializesModels;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(array $form_data)
	{
		$this->data['input'] = $form_data['input'];
	}

	public function job(){	
		$audit_trail = new AuditTrail;
		$audit_trail->fill($this->data['input']);
		$audit_trail->ip_address = Request::ip();
		$audit_trail->save();
	}
}
