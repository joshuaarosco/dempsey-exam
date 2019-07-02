<?php

namespace Api\Auth\Controllers;

use Core\Foundation\Controller;
use Core\Traits\SendsResponseData;

class LoginController extends Controller
{
	use SendsResponseData;

	/**
     * Main authentication logic.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        return 1;
    }
}