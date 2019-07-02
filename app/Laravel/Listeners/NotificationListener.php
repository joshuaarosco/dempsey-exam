<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\NotificationEvent;

class NotificationListener{

	public function handle(NotificationEvent $email){
		$email->job();

	}
}