<?php 

namespace App\Laravel\Traits;

trait StatusBadgeTrait{

	protected $status_field = "status";

	public function set_status_field($field_name) {
		$this->status_field = $field_name;
	}

	public function status_badge() {

		$status = strtoupper($this->{$this->status_field});

		switch ($status) {
			case 'GRANTED':
			case 'COMPLETED':
				return "<span class='tag tag-pill tag-default tag-success'>" . $status . "</span>";
			break;
			
			case 'DENIED':
				return "<span class='tag tag-pill tag-default tag-danger'>" . $status . "</span>";
			break;

			default:
				return "<span class='tag tag-pill tag-default tag-default'>" . $status . "</span>";
			break;
		}
	}

}