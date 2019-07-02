<?php namespace App\Laravel\Listeners;

use App\Laravel\Events\PusherEvent;

class PusherEventListener{

	public function handle(PusherEvent $pusher){
		$pusher->job();
	}
}