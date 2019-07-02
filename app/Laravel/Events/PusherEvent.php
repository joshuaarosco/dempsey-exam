<?php 

namespace App\Laravel\Events;

use Pusher\Pusher;
use App\Laravel\Models\Security\AuditTrail;
use Illuminate\Queue\SerializesModels;
use Mail,Str,Request,Carbon,Helper;
// use App\Constech\Models\GeneralSetting;

class PusherEvent extends Event {

	use SerializesModels;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(array $form_data)
	{
		$this->data['id'] = $form_data['id'];
		$this->data['thread_id'] = $form_data['thread_id'];
		$this->data['sender_id'] = $form_data['sender_id'];
		$this->data['status'] = $form_data['status'];
		$this->data['message'] = $form_data['message'];
		$this->data['avatar'] = $form_data['avatar'];
	}

	public function job(){
		$options = array(
         	'cluster' => env('PUSHER_APP_CLUSTER'),
         	'useTLS' => true
        );

        $pusher = new Pusher(
	        env('PUSHER_APP_KEY'),
	        env('PUSHER_APP_SECRET'),
	        env('PUSHER_APP_ID'),
	        $options
        );

        $data['id'] = $this->data['id'];
        $data['thread_id'] = $this->data['thread_id'];
        $data['sender_id'] = $this->data['sender_id'];
        $data['status'] = $this->data['status'];
        $data['message'] = $this->data['message'];
        $data['time'] = Helper::date_format(Carbon::now(),'D h:i A');
        $data['avatar'] = $this->data['avatar'];
        $pusher->trigger($this->data['thread_id'], 'message-event', $data);
	}
}
