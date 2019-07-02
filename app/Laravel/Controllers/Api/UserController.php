<?php 

namespace App\Laravel\Controllers\Api;

use App\Laravel\Models\User;
use App\Laravel\Requests\Api\SearchRequest;
use App\Laravel\Transformers\TransformerManager;
use App\Laravel\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Helper, Str;

class UserController extends Controller{

	protected $response = array();

	public function __construct(){
		$this->response = array(
			"msg" => "Bad Request.",
			"status" => FALSE,
			'status_code' => "BAD_REQUEST"
			);
		$this->response_code = 400;
		$this->transformer = new TransformerManager;
	}

    public function all($format = ""){
		try{
			$this->response['msg'] = "User Lists";
			$this->response['status_code'] = "USER_LISTS";
			$this->response['status'] = TRUE;
			$this->response_code = 200;
			$user = User::where('type','employee')->get();
			$this->response['data'] = $this->transformer->transform($user,new UserTransformer,'collection');

			callback:

			switch(Str::lower($format)){
				case 'json' :
				
				return response()->json($this->response,$this->response_code);

				break;

				default :
				$this->response['msg'] = "Invalid return data format.";
				$this->response['status_code'] = "INVALID_FORMAT";
				$this->response['status'] = FALSE;
				$this->response_code = 406;
				return response()->json($this->response,$this->response_code);
			}


		}catch(Exception $e){
			$this->response_code = 500;
			$this->response['msg']	= $e->getMessage();
			$this->response['status_code'] = "ERROR_EXCEPTION";
			return response()->json($this->response,$this->response_code);
		}
	}
}