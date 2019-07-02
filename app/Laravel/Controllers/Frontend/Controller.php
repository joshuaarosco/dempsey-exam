<?php 

namespace App\Laravel\Controllers\Frontend;

use App\Laravel\Models\User;
use App\Laravel\Models\Cms\Blog;
use App\Laravel\Models\Cms\Client;
use App\Laravel\Models\Cms\WebInfo;
use App\Laravel\Models\Cms\Service;
use App\Laravel\Models\Cms\Portfolio;
use App\Laravel\Models\Cms\SocialLink;
use App\Laravel\Models\Cms\PageContent;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller as MainController;
use Auth, Session,Carbon, Helper, Route, DNS2D, Request, Input;

class Controller extends MainController{

	protected $data;

	public function __construct(){
	}

	public function get_data(){
		return $this->data;
	}

}