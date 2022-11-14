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

class vendorController extends Controller
{
	public $emptyarray = array();
	public function createvendor(Request $request){
		$checkemail = DB::table('vendor')
		->select('vendor_email')
		->where('vendor_email','=',$request->vendor_email)
		->where('status_id','=',1)
		->first();
		if (isset($checkemail)) {
			return response()->json("Vendor Email Already Exist", 400);
		}
		$validate = Validator::make($request->all(), [ 
	      'vendor_name' 	=> 'required',
	      'vendor_email'	=> 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
		'vendor_name' 				=> $request->vendor_name,
		'vendor_email'				=> $request->vendor_email,
		'vendor_altemail' 			=> $request->vendor_altemail,
		'vendor_phone' 				=> $request->vendor_phone,
		'vendor_zip' 				=> $request->vendor_zip,
		'vendor_address' 			=> $request->vendor_address,
		'vendor_bussinessname' 		=> $request->vendor_bussinessname,
		'vendor_bussinessemail'		=> $request->vendor_bussinessemail,
		'vendor_bussinesswebsite' 	=> $request->vendor_bussinesswebsite,
		'vendor_bussinessphone' 	=> $request->vendor_bussinessphone,
		'vendor_otherdetails' 		=> $request->vendor_otherdetails,
		'vendor_date' 				=> date('Y-m-d'),
		'status_id'		 			=> 1,
		'created_by'	 			=> $request->user_id,
		'created_at'	 			=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('vendor')->insert($adds);
		if($save){
			return response()->json(['message' => 'Vendor Created Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function updatevendor(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'vendor_id' 				=> 'required',
	      'vendor_name' 			=> 'required',
	      'vendor_email'			=> 'required',
	      'vendor_altemail' 		=> 'required',
	      'vendor_phone'			=> 'required',
	      'vendor_zip'				=> 'required',
	      'vendor_address'			=> 'required',
	      'vendor_bussinessname' 	=> 'required',
	      'vendor_bussinessemail'	=> 'required',
	      'vendor_bussinesswebsite' => 'required',
	      'vendor_bussinessphone'	=> 'required',
	      'vendor_otherdetails' 	=> 'required',
	    ]);
	 	if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		$checkemail = DB::table('vendor')
		->select('vendor_email')
		->where('vendor_id','=',$request->vendor_id)
		->where('status_id','=',1)
		->first();
		if ($checkemail->vendor_email != $request->vendor_email) {
			$validateunique = Validator::make($request->all(), [ 
		      'vendor_email' 		=> 'unique:vendor,vendor_email',
		    ]);
     	if ($validateunique->fails()) {    
			return response()->json("Vendor Email Already Exist", 400);
		}
		}
		$updatevendor  = DB::table('vendor')
		->where('vendor_id','=',$request->vendor_id)
		->update([
		'vendor_name' 				=> $request->vendor_name,
		'vendor_email'				=> $request->vendor_email,
		'vendor_altemail' 			=> $request->vendor_altemail,
		'vendor_phone' 				=> $request->vendor_phone,
		'vendor_zip' 				=> $request->vendor_zip,
		'vendor_address' 			=> $request->vendor_address,
		'vendor_bussinessname' 		=> $request->vendor_bussinessname,
		'vendor_bussinessemail'		=> $request->vendor_bussinessemail,
		'vendor_bussinesswebsite' 	=> $request->vendor_bussinesswebsite,
		'vendor_bussinessphone' 	=> $request->vendor_bussinessphone,
		'vendor_otherdetails' 		=> $request->vendor_otherdetails,
		'status_id'		 			=> 1,
		'updated_by'	 			=> $request->user_id,
		'updated_at'	 			=> date('Y-m-d h:i:s'),
		]);
		if($updatevendor){
			return response()->json(['message' => 'Vendor Updated Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function vendorlist(Request $request){
		$getvendorlist = DB::table('vendor')
		->select('*')
		->where('status_id','=',1)
		->orderBy('vendor_id','DESC')
		->get();
		if(isset($getvendorlist)){
			return response()->json(['data' => $getvendorlist,'message' => 'Vendor List'],200);
		}else{
			return response()->json(['data' => $emptyarray, 'message' => 'Vendor List'],200);
		}
	}
	public function vendordetails(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'vendor_id'		=> 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json("Vendor Id Required", 400);
		}
		$getdetails = DB::table('vendor')
		->select('*')
		->where('vendor_id','=',$request->vendor_id)
		->where('status_id','=',1)
		->first();
		if($getdetails){
			return response()->json(['data' => $getdetails,'message' => 'Vendor Details'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function deletevendor(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'vendor_id'			=> 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json("Vendor Id Required", 400);
		}
		$update  = DB::table('vendor')
		->where('vendor_id','=',$request->vendor_id)
		->update([
		'status_id' 	=> 2,
		'deleted_by'	=> $request->user_id,
		'deleted_at'	=> date('Y-m-d h:i:s'),
		]); 
		if($update){
			return response()->json(['message' => 'Vendor Deleted Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
}