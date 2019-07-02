<?php


/**
 *
 * ------------------------------------
 * Backoffice Routes
 * ------------------------------------
 *
 */

Route::group(

	array(
		'as' => "backoffice.",
		'prefix' => "admin",
		'namespace' => "Backoffice",
	),

	function() {

		$this->group(['as'=>"auth.", 'middleware' => ["web","backoffice.guest"]], function(){
			$this->get('login',['as' => "login",'uses' => "AuthController@login"]);
			$this->post('login',['uses' => "AuthController@authenticate"]);
		});

		$this->group(['middleware' => ["backoffice.auth","backoffice.employee_only"]], function(){

			$this->get('/',['as' => "dashboard",'uses' => "DashboardController@index"]);
			$this->get('logout',['as' => "logout",'uses' => "AuthController@destroy"]);

			$this->group(['prefix' => "profile", 'as' => "profile."], function () {
				$this->get('/',['as' => "settings", 'uses' => "ProfileController@setting"]);
				$this->post('/',['uses' => "ProfileController@update_setting"]);
				$this->post('update-password',['as' => "update_password",'uses' => "ProfileController@update_password"]);
			});

			$this->group(['prefix' => "employees", 'as' => "employees.",'middleware' => "backoffice.super_user_only"], function () {
				$this->get('/',['as' => "index", 'uses' => "EmployeeController@index"]);
				$this->post('create',['as' => "store",'uses' => "EmployeeController@store"]);
				$this->post('edit/{id?}',['as' => "update", 'uses' => "EmployeeController@update"]);
				$this->any('delete/{id?}',['as' => "destroy", 'uses' => "EmployeeController@destroy"]);

				$this->post('search',['as' => "search", 'uses' => "EmployeeController@search"]);

				$this->post('pay',['as' => "add_pay", 'uses' => "EmployeeController@add_pay"]);
				$this->any('pay-history',['as' => "pay_history", 'uses' => "EmployeeController@pay_history"]);
			});

		});
	}
);