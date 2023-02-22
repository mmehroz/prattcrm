<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Image;
use DB;
use Input;
use App\Item;
use Session;
use Response;
use Validator;

class settingsController extends Controller
{
	public $emptyarray = array();
	public function rolelist(Request $request){
		$getroles = DB::table('role')
		->select('role_id','role_name')
		->where('status_id','=',1)
		->get();
		if (isset($getroles)) {
			return response()->json(['data' => $getroles,'message' => 'CRM Roles'],200);
		}else{
			return response()->json(['data' => $emptyarray, 'message' => 'CRM Roles'],200);
		}
	}
}