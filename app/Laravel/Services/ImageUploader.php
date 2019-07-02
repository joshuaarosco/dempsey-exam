<?php 

namespace App\Laravel\Services;

/*
*
* Models used for this class
*/

/*
*
* Classes used for this class
*/
use Carbon, Str, File, Image, AzureStorage, URL;

class ImageUploader {

	/**
	*
	*@param App\Http\Requests\RequestRequest $request
	*@param string $request
	*
	*@return array
	*/
	public static function upload($file, $image_directory = "uploads"){
		
		$storage = env('IMAGE_STORAGE', "file");


		switch (Str::lower($storage)) {
			case 'file':
				// $file = $request->file("file");
				$ext = $file->getClientOriginalExtension();
				$thumbnail = ['height' => 500, 'width' => 500];
				$path_directory = $image_directory."/".Carbon::now()->format('Ymd');
				$resized_directory = $image_directory."/".Carbon::now()->format('Ymd')."/resized";
				$thumb_directory = $image_directory."/".Carbon::now()->format('Ymd')."/thumbnails";

				if (!File::exists($path_directory)){
					File::makeDirectory($path_directory, $mode = 0777, true, true);
				}

				if (!File::exists($resized_directory)){
					File::makeDirectory($resized_directory, $mode = 0777, true, true);
				}

				if (!File::exists($thumb_directory)){
					File::makeDirectory($thumb_directory, $mode = 0777, true, true);
				}

				$filename = Helper::create_filename($ext);

				$file->move($path_directory, $filename); 
				if(in_array(Str::lower($ext), ['jpg','png','jpeg','gif','webp'])){
					Image::make("{$path_directory}/{$filename}")->save("{$resized_directory}/{$filename}",95);
					Image::make("{$path_directory}/{$filename}")->crop($thumbnail['width'],$thumbnail['height'])->save("{$thumb_directory}/{$filename}",95);
				}

				return [ "path" => $image_directory, "directory" => URL::to($path_directory), "filename" => $filename ];
			break;

			case 'azure':
				// $file = $request->file('file');
				$ext = $file->getClientOriginalExtension();
				$thumbnail = ['height' => 500, 'width' => 500];

				$path_directory = "{$image_directory}/".Carbon::now()->format('Ymd');
				$resized_directory = "{$image_directory}/".Carbon::now()->format('Ymd')."/resized";
				$thumb_directory = "{$image_directory}/".Carbon::now()->format('Ymd')."/thumbnails";

				if (!File::exists($path_directory)){
					File::makeDirectory($path_directory, $mode = 0777, true, true);
				}

				if (!File::exists($resized_directory)){
					File::makeDirectory($resized_directory, $mode = 0777, true, true);
				}

				if (!File::exists($thumb_directory)){
					File::makeDirectory($thumb_directory, $mode = 0777, true, true);
				}

				$filename = Helper::create_filename($ext);
				$new_image_filename = $filename;
				$file->move($path_directory, $filename); 

				// if(Image::make("{$path_directory}/{$filename}")->width() > Image::make("{$path_directory}/{$filename}")->height()){
				// 	Image::make("{$path_directory}/{$filename}")->resize(null, 512, function ($constraint) {
				// 	    $constraint->aspectRatio();
				// 	})->orientate()->save("{$resized_directory}/{$filename}",95);
				// 	Image::make("{$path_directory}/{$filename}")->resize(null, 256, function ($constraint) {
				// 	    $constraint->aspectRatio();
				// 	})->orientate()->save("{$thumb_directory}/{$filename}",90);

				// }else{
				// 	Image::make("{$path_directory}/{$filename}")->resize(512, null, function ($constraint) {
				// 	    $constraint->aspectRatio();
				// 	})->orientate()->save("{$resized_directory}/{$filename}",95);
				// 	Image::make("{$path_directory}/{$filename}")->resize(256, null, function ($constraint) {
				// 	    $constraint->aspectRatio();
				// 	})->orientate()->save("{$thumb_directory}/{$filename}",90);
				// }

				if(in_array($ext, ['jpg','png','jpeg','gif','JPG'])){
					Image::make("{$path_directory}/{$filename}")->save("{$resized_directory}/{$filename}",95);
					Image::make("{$path_directory}/{$filename}")->crop($thumbnail['width'],$thumbnail['height'])->save("{$thumb_directory}/{$filename}",95);
				}
				
				$client = new AzureStorage(env('BLOB_STORAGE_URL'),env('BLOB_ACCOUNT_NAME'),env('BLOB_ACCESS_KEY'));
				
				$container= env('BLOB_CONTAINER');
				$orig_container = env('BLOB_ORIG_CONTAINER');
				$directory = env('BLOB_STORAGE_URL')."/".env('BLOB_CONTAINER');

				// $new_image_directory = "{$directory}/{$path_directory}";
				// $new_image_path = "{$path_directory}";

				$new_image_directory = "{$directory}/".str_replace("uploads/", "", $path_directory); 
				$new_image_path = str_replace("uploads/", "", $path_directory);
				
				$client->putBlob($orig_container, "{$new_image_path}/{$filename}", "{$path_directory}/{$filename}");
				$client->putBlob($container, "{$new_image_path}/thumbnails/{$filename}", "{$path_directory}/thumbnails/{$filename}");
				$client->putBlob($container, "{$new_image_path}/resized/{$filename}", "{$path_directory}/resized/{$filename}");
			
				if (File::exists("{$path_directory}/{$filename}")){
					File::delete("{$path_directory}/{$filename}");
				}
				if (File::exists("{$path_directory}/thumbnails/{$filename}")){
					File::delete("{$path_directory}/thumbnails/{$filename}");
				}
				if (File::exists("{$path_directory}/resized/{$filename}")){
					File::delete("{$path_directory}/resized/{$filename}");
				}

				return [ "path" => $new_image_path, "directory" => $new_image_directory, "filename" => $new_image_filename ];
			break;
			
			default:
				return array();
			break;
		}
	}
}