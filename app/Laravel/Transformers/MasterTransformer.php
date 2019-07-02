<?php

namespace App\Laravel\Transformers;

use League\Fractal\TransformerAbstract;
use DB,Helper,Str,Cache,Carbon;

class MasterTransformer extends TransformerAbstract{
	public function transform($item){
		$result = [];
		foreach($item as $key => $value){
			$result[$key] = $value;
		}	

		return $result;
	}
}