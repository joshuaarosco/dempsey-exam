<?php 

namespace App\Laravel\Events;


use App\Laravel\Models\ReferAndTransactionDb\Notification;
use Illuminate\Queue\SerializesModels;
use Mail,Str,Request;
// use App\Constech\Models\GeneralSetting;

class NotificationEvent extends Event {

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
		$notification = new Notification;
		$notification->fill($this->data['input']);
		$notification->save();
	}
}
