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

class categoryController extends Controller
{
	public $emptyarray = array();
	public function createcategory(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'category_name' 	=> 'required',
          'vendor_id' 	    => 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
		'category_name' 	=> $request->category_name,
		'category_slug'		=> str_slug($request->category_name),
		'vendor_id' 		=> $request->vendor_id,
		'status_id'		 	=> 1,
		'created_by'	 	=> $request->user_id,
		'created_at'	 	=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('category')->insert($adds);
		if($save){
			return response()->json(['message' => 'Category Created Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function updatecategory(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'category_id' 	=> 'required',
	      'category_name' 	=> 'required',
	      'vendor_id'		=> 'required',
	    ]);
	 	if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
        $data = array(
            'category_name' 	=> $request->category_name,
            'category_slug'		=> str_slug($request->category_name),
            'vendor_id' 		=> $request->vendor_id,
            'updated_by'	 	=> $request->user_id,
            'updated_at'	 	=> date('Y-m-d h:i:s'),
         );
		$updatecategory  = DB::table('category')
		->where('category_id','=',$request->category_id)
		->update($data);
		if($updatecategory){
			return response()->json(['message' => 'Category Updated Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function categorylist(Request $request){
		$getcategorylist = DB::table('category')
		->select('*')
		->where('status_id','=',1)
		->orderBy('category_id','DESC')
		->get();
		if(isset($getcategorylist)){
			return response()->json(['data' => $getcategorylist,'message' => 'Category List'],200);
		}else{
			return response()->json(['data' => $emptyarray, 'message' => 'Category List'],200);
		}
	}
	public function categorydetails(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'category_id'		=> 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json("Category Id Required", 400);
		}
		$getdetails = DB::table('categorydetails')
		->select('*')
		->where('category_id','=',$request->category_id)
		->where('status_id','=',1)
		->first();
		if($getdetails){
			return response()->json(['data' => $getdetails,'message' => 'Category Details'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function deletecategory(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'category_id'			=> 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json("Category Id Required", 400);
		}
		$update  = DB::table('category')
		->where('category_id','=',$request->category_id)
		->update([
		'status_id' 	=> 2,
		'deleted_by'	=> $request->user_id,
		'deleted_at'	=> date('Y-m-d h:i:s'),
		]); 
		if($update){
			return response()->json(['message' => 'Category Deleted Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
}