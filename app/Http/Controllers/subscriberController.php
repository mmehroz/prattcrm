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

class subscriberController extends Controller
{
	public $emptyarray = array();
	public function createsubscriber(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'subscriber_firstname' 		=> 'required',
          'subscriber_lastname' 		=> 'required',
		  'subscriber_email' 			=> 'required',
		  'subscriber_contactnumber' 	=> 'required',
        ]);
     	if ($validate->fails()) {
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
		'subscriber_id' 			=> $request->subscriber_id,
		'subscriber_firstname' 		=> $request->subscriber_firstname,
		'subscriber_lastname'		=> $request->subscriber_lastname,
		'subscriber_email' 			=> $request->subscriber_email,
		'subscriber_contactnumber'	=> $request->subscriber_contactnumber,
		'subscriber_sellerId' 		=> $request->subscriber_sellerId,
		'subscriber_externalUserId'	=> $request->subscriber_externalUserId,
		'subscriber_isDeleted' 		=> "false",
		'subscriber_city'			=> $request->subscriber_city,
		'subscriber_state' 			=> $request->subscriber_state,
		'subscriber_zipCode'		=> $request->subscriber_zipCode,
		'subscriber_streetAddress' 	=> $request->subscriber_streetAddress,
		'subscriber_addressLine2'	=> $request->subscriber_addressLine2,
		'subscriber_date'			=> date('Y-m-d'),
		'status_id'		 			=> 1,
		'created_by'				=> $request->user_id,
		'created_at'	 			=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('subscriber')->insert($adds);
		$subscriber_id = DB::getPdo()->lastInsertId();
		if (isset($request->activations)) {
			foreach ($request->activations as $activations) {
				$activations = array(
				'activations_phonenumber'		=> $activations['activations_phonenumber'],
				'activations_imei'				=> $activations['activations_imei'],
				'activations_size'				=> $activations['activations_size'],
				'activations_tethering'			=> $activations['activations_tethering'],
				'activations_servicetype'		=> $activations['activations_servicetype'],
				'activations_servicezipcode'	=> $activations['activations_servicezipcode'],
				'activations_planname' 			=> $activations['activations_planname'],
				'activations_firstname' 		=> $activations['activations_firstname'],
				'activations_lastname' 			=> $activations['activations_lastname'],
				'activations_status' 			=> $activations['activations_status'],
				'activations_iccid' 			=> $activations['activations_iccid'],
				'activations_intlRoaming' 		=> $activations['activations_intlRoaming'],
				'activations_intlcall' 			=> $activations['activations_intlcall'],
				'activations_date'	 			=> date('Y-m-d'),
				'subscriber_id'					=> $subscriber_id,
				'status_id' 					=> 1,
				'created_by'					=> $request->user_id,
				'created_at'					=> date('Y-m-d h:i:s'),
				);
				DB::table('activations')->insert($payment);
			}
		}
		if(isset($request->usage)) {
			foreach($request->usage as $usage) {
				$adds = array(
				'usage_ms_i_roaming_o' 		=> $usage['usage_ms_i_roaming_o'],
				'usage_ms_roaming_o' 		=> $usage['usage_ms_roaming_o'],
				'usage_ms_home_o' 			=> $usage['usage_ms_home_o'],
				'usage_voice_home_o' 		=> $usage['usage_voice_home_o'],
				'usage_voice_roaming_o' 	=> $usage['usage_voice_roaming_o'],
				'usage_voice_i_roaming_o'	=> $usage['usage_voice_i_roaming_o'],
				'usage_data_hotspot' 		=> $usage['usage_data_hotspot'],
				'usage_data_broadband' 		=> $usage['usage_data_broadband'],
				'usage_home_o' 				=> $usage['usage_home_o'],
				'usage_roaming_o' 			=> $usage['usage_roaming_o'],
				'usage_data_i_roaming_o' 	=> $usage['usage_data_i_roaming_o'],
				'usage_sms_i_roaming_o' 	=> $usage['usage_sms_i_roaming_o'],
				'usage_sms_roaming_o' 		=> $sage['usage_sms_roaming_o'],
				'usage_sms_home_o'			=> $usage['usage_sms_home_o'],
				);
			}
		}
		if($save){
			return response()->json(['message' => 'Subscriber Created Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function updatesubscriber(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'subscriber_id' 				=> 'required',
	      'subscriber_firstname' 		=> 'required',
	      'subscriber_lastname' 		=> 'required',
		  'subscriber_email' 			=> 'required',
		  'subscriber_contactnuber' 	=> 'required',
		  'subscriber_externaluserid' 	=> 'required',
		  'subscriber_address' 			=> 'required',
		]);
	 	if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
        $data = array(
            'subscriber_firstname' 		=> $request->subscriber_firstname,
            'subscriber_lastname'		=> $request->subscriber_lastname,
			'subscriber_email' 			=> $request->subscriber_email,
			'subscriber_contactnumber' 	=> $request->subscriber_contactnumber,
			'subscriber_externaluserid' => $request->subscriber_externaluserid,
			'subscriber_streetaddress' 	=> $request->subscriber_streetaddress,
			'subscriber_addressline2' 	=> $request->subscriber_addressline2,
			'subscriber_city' 			=> $request->subscriber_city,
			'subscriber_state' 			=> $request->subscriber_state,
			'subscriber_zipcode' 		=> $request->subscriber_zipcode,
			'updated_by'				=> $request->user_id,
			'updated_at'				=> date('Y-m-d h:i:s'),
        );
		$updatesubscriber  = DB::table('subscriber')
		->where('subscriber_id','=',$request->subscriber_id)
		->update($data);
		if($updatesubscriber){
			return response()->json(['message' => 'Subscriber Updated Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function subscriberlist(Request $request){
		$getsubscriberlist = DB::table('subscriber')
		->select('*')
		->where('status_id','=',1)
		->orderBy('subscriber_id','DESC')
		->get();
		if(isset($getsubscriberlist)){
			return response()->json(['data' => $getsubscriberlist,'message' => 'Subscriber List'],200);
		}else{
			return response()->json(['data' => $emptyarray, 'message' => 'Subscriber List'],200);
		}
	}
	public function subscriberdetails(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'subscriber_id'	=> 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json("Subscriber Id Required", 400);
		}
		$personal = DB::table('subscriber')
		->select('*')
		->where('subscriber_id','=',$request->subscriber_id)
		->where('status_id','=',1)
		->first();
		$activations = DB::table('activations')
		->select('*')
		->where('subscriber_id','=',$request->subscriber_id)
		->where('status_id','=',1)
		->get();
		$usage = DB::table('usage')
		->select('*')
		->where('subscriber_id','=',$request->subscriber_id)
		->where('status_id','=',1)
		->get();
		if($personal){
			return response()->json(['personal' => $personal, 'activations' => $activations, 'usage' => $usage, 'message' => 'Subscriber Details'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function deletesubscriber(Request $request){
		$validate = Validator::make($request->all(), [
	      'subscriber_id'	=> 'required',
	    ]);
     	if ($validate->fails()) {  
			return response()->json("Subscriber Id Required", 400);
		}
		$update  = DB::table('subscriber')
		->where('subscriber_id','=',$request->subscriber_id)
		->update([
			'status_id' 	=> 2,
			'deleted_by'	=> $request->user_id,
			'deleted_at'	=> date('Y-m-d h:i:s'),
		]);
		DB::table('activations')
		->where('subscriber_id','=',$request->subscriber_id)
		->update([
			'status_id' 	=> 2,
			'deleted_by'	=> $request->user_id,
			'deleted_at'	=> date('Y-m-d h:i:s'),
		]);
		DB::table('usage')
		->where('subscriber_id','=',$request->subscriber_id)
		->update([
			'status_id'   	=> 2,
			'deleted_by' 	=> $request->user_id,
			'deleted_at' 	=> date('Y-m-d h:i:s')
		]);
		if($update){
			return response()->json(['message' => 'Subscriber Deleted Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
}