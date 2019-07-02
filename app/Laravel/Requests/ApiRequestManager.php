<?php 

namespace App\Laravel\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiRequestManager extends FormRequest {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	public function response(array $errors){

		$format = $this->route('format');

		$response = [
			'msg' => "Incomplete or invalid input",
			'status' => FALSE,
			'status_code' => "INVALID_DATA",
			'errors' => $errors,
		];

		switch ($format) {
			case 'json':
				return response()->json($response, 422);
			break;
			case 'xml':
				return response()->xml($response, 422);
			break;
		}
	}
}