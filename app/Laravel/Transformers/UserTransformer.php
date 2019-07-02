<?php 

namespace App\Laravel\Transformers;

use App\Laravel\Models\User;

use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Laravel\Transformers\MasterTransformer;
use JWTAuth, Helper;

class UserTransformer extends TransformerAbstract{

	protected $availableIncludes = [

    ];

	public function transform(User $user) {
	     return [
	     	'id' => $user->id ?:0,
	     	'fname' => $user->fname,
	     	'lname' => $user->lname,
	     	'birthdate' => $user->birthdate,
	     	'contact_number' => $user->contact_number,
	     	'email' => $user->email,
			'image' => $user->directory?"{$user->directory}/resized/{$user->filename}":"",
	     	'balance' => $user->payment($user->id),
	     	'pay_history'=> $user->payment_history($user->id),
	     	'updated_at' => $user->updated_at,
	     	'created_at' => $user->created_at,
	     ];
	}
}