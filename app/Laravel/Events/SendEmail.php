<?php namespace App\Laravel\Events;


use App\Laravel\Models\ContactInfo;
use Illuminate\Queue\SerializesModels;
use Mail,Str;
// use App\Constech\Models\GeneralSetting;

class SendEmail extends Event {

	use SerializesModels;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(array $form_data)
	{
		$this->data['input'] = $form_data['input'];
		$this->data['type'] = $form_data['type'];
	}

	public function job(){	
		$bytes = openssl_random_pseudo_bytes(5);
		$this->data['rand'] = Str::upper(bin2hex($bytes));
		switch ($this->data['type']) {

			case 'approve_scholarship':
				
				$data = ['email' => $this->data['input']['email'],'username' => $this->data['input']['email'],'password' => $this->data['input']['password'],];

				Mail::send('emails.employee_password', $data, function($message){
					$message->from('noreply@domain.com',"BPC OSAMS");
					$message->to($this->data['input']['email']);
					$message->subject("#BPC-".$this->data['rand']." - BPC OSAMS.");
				});

			break;
			
			default:
				$data = [
					'fname' => $this->data['input']['fname'],
					'lname' => $this->data['input']['lname'],
					'contact_number' => $this->data['input']['contact_number'],
					'email' => $this->data['input']['email'],
					'purpose' => $this->data['input']['purpose'],
					'subject' => $this->data['input']['subject'],
					'msg' => $this->data['input']['message'],
			];

				Mail::send('emails.meeting_request', $data, function($message){
					$message->from('noreply@domain.com',"Carla Mantanan");
					$message->to($this->data['input']['email']);
					$message->subject("Carla Meeting Request #CAR-".$this->data['rand'].".");
				});
			break;
		}
	}
}
