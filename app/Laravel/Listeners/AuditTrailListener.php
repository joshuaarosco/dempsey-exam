<?php namespace App\Laravel\Listeners;

use App\Laravel\Events\AuditTrailEvent;

class AuditTrailListener{

	public function handle(AuditTrailEvent $audit_trail){
		$audit_trail->job();

	}
}