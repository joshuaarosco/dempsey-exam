<?php 

namespace App\Laravel\Transformers;

use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\DataArraySerializer;
use Input;

class TransformerManager
{
	public function transform($data, $transformer, $type = 'item')
	{
		$manager = new Manager();
		$manager->setSerializer(new DataArraySerializer());

		if(Input::has('include')) {
		    $manager->parseIncludes(Input::get('include'));
		}

		if($type == 'item')
		{
			$resource = new Item($data, $transformer);
		}
		else
		{
			$resource = new Collection($data, $transformer);
		}
		
		$data = $manager->createData($resource)->toArray();

		// We want to return data key
		return $data['data'];
	}
}
