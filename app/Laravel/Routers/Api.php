<?php


/**
 *
 * ------------------------------------
 * Api Routes
 * ------------------------------------
 *
 */

Route::group(

	array(
		'as' => "api.",
		'namespace' => "Api"
	),

	function() {

		$this->group(['as' => "employees.", 'prefix' => "employees"], function() {
			$this->any('all.{format?}', [ 'as' => "all", 'uses' => "UserController@all"]);
		});
	}
);