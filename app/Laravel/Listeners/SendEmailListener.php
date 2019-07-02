<?php namespace App\Laravel\Listeners;

use App\Laravel\Events\SendEmail;

class SendEmailListener{

	public function handle(SendEmail $email){
		$email->job();

	}
}