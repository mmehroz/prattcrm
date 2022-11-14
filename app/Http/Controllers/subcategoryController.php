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

class subcategoryController extends Controller
{
	public $emptyarray = array();
	public function createsubcategory(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'subcategory_name' 	=> 'required',
          'category_id' 	    => 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
		'subcategory_name' 	=> $request->subcategory_name,
		'subcategory_slug'	=> str_slug($request->subcategory_name),
		'category_id' 		=> $request->category_id,
		'status_id'		 	=> 1,
		'created_by'	 	=> $request->user_id,
		'created_at'	 	=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('subcategory')->insert($adds);
		if($save){
			return response()->json(['message' => 'SubCategory Created Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function updatesubcategory(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'subcategory_id' 	    => 'required',
	      'subcategory_name' 	=> 'required',
	      'category_id'		    => 'required',
	    ]);
	 	if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
        $data = array(
            'subcategory_name' 	=> $request->subcategory_name,
            'subcategory_slug'	=> str_slug($request->subcategory_name),
            'category_id' 		=> $request->category_id,
            'updated_by'	 	=> $request->user_id,
            'updated_at'	 	=> date('Y-m-d h:i:s'),
         );
		$updatesubcategory  = DB::table('subcategory')
		->where('subcategory_id','=',$request->subcategory_id)
		->update($data);
		if($updatesubcategory){
			return response()->json(['message' => 'SubCategory Updated Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function subcategorylist(Request $request){
		$getsubcategorylist = DB::table('subcategory')
		->select('*')
		->where('status_id','=',1)
		->orderBy('subcategory_id','DESC')
		->get();
		if(isset($getsubcategorylist)){
			return response()->json(['data' => $getsubcategorylist,'message' => 'SubCategory List'],200);
		}else{
			return response()->json(['data' => $emptyarray, 'message' => 'SubCategory List'],200);
		}
	}
	public function subcategorydetails(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'subcategory_id'		=> 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json("subCategory Id Required", 400);
		}
		$getdetails = DB::table('subcategorydetails')
		->select('*')
		->where('subcategory_id','=',$request->subcategory_id)
		->where('status_id','=',1)
		->first();
		if($getdetails){
			return response()->json(['data' => $getdetails,'message' => 'SubCategory Details'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function deletesubcategory(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'subcategory_id'			=> 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json("SubCategory Id Required", 400);
		}
		$update  = DB::table('subcategory')
		->where('subcategory_id','=',$request->subcategory_id)
		->update([
		'status_id' 	=> 2,
		'deleted_by'	=> $request->user_id,
		'deleted_at'	=> date('Y-m-d h:i:s'),
		]); 
		if($update){
			return response()->json(['message' => 'subCategory Deleted Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
}