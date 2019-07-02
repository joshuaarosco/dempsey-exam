<?php

Route::prefix('auth')->namespace('Auth\Controllers')->group(function($router) {
	$router->post('login.{format?}', "LoginController@login")->name('login');
});