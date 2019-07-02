<?php


/**
 *
 * ------------------------------------
 * Frontend Routes
 * ------------------------------------
 *
 */

$this->group(

	array(
		'as' => "frontend.",
		'namespace' => "Frontend",
	),

	function() {

		$this->get('/',['as' => "index",'uses' => "PageController@index"]);

	}
);