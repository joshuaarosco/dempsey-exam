<?php

namespace Core\Traits;

use Illuminate\Support\Facades\Request;

trait SendsResponseData
{
	/*
	 * Sends a response data
	 * 
	 * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
	 */
	public function sendResponse($payload, $httpstatus)
	{
		switch (strtolower(Request::route('format'))) {
			case 'xml':
				return response()->xml($payload, $httpstatus);
			break;
			default:
				return response()->json($payload, $httpstatus);
			break;
		}
	}
}