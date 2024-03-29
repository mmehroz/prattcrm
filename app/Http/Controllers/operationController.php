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

class operationController extends Controller
{
	public $emptyarray = array();
	public function createoperation(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'operation_id' 					=> 'required',
          'operation_phonenumber' 			=> 'required',
		  'operation_portinstatus' 			=> 'required',
		  'operation_billingaccountnumber' 	=> 'required',
		  'operation_updated_at' 			=> 'required',
		  'operation_activation_updated_at'	=> 'required',
		  'operation_sellerid' 				=> 'required',
		  'operation_subscriberid' 			=> 'required',
		  'operation_status' 				=> 'required',
		  'operation_activationid' 			=> 'required',
		  'operation_activationstatusid'	=> 'required',
		  'operation_activationdate' 		=> 'required',
		  'operation_imei' 					=> 'required',
		  'operation_iccid' 				=> 'required',
		  'operation_subscribername' 		=> 'required',
		  'operation_size' 					=> 'required',
		  'operation_mvno' 					=> 'required',
		  'operation_activationtype' 		=> 'reqired',
        ]);
     	if ($validate->fails()) {
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'operation_id' 						=> $request->operation_id,
			'operation_phonenumber' 			=> $request->operation_phonenumber,
			'operation_portinstatus' 			=> $request->operation_portinstatus,
			'operation_billingaccountnumber' 	=> $request->operation_billingaccountnumber,
			'operation_updated_at' 				=> $request->operation_updated_at,
			'operation_activation_updated_at' 	=> $request->operation_activation_updated_at,
			'operation_sellerid' 				=> $request->operation_sellerid,
			'operation_subscriberid' 			=> $request->operation_subscriberid,
			'operation_status' 					=> $request->operation_status,
			'operation_activationid' 			=> $request->operation_activationid,
			'operation_activationstatusid' 		=> $request->operation_activationstatusid,
			'operation_activationdate' 			=> $request->operation_activationdate,
			'operation_imei' 					=> $request->operation_imei,
			'operation_iccid' 					=> $request->operation_iccid,
			'operation_subscribername' 			=> $request->operation_subscribername,
			'operation_size' 					=> $request->operation_size,
			'operation_mvno' 					=> $request->operation_mvno,
			'operation_activationtype' 			=> $request->operation_activationtype,
			'status_id'							=> 1,
			'created_by' 						=> $request->user_id,
			'created_at' 						=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('opeartion')->insert($adds);
		if($save){
			return response()->json(['message' => 'Operation Created Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function updateoperation(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'operation_mainid'				=> 'required',
		  'operation_id' 					=> 'required',
	      'operation_phonenumber' 			=> 'required',
	      'operation_portinstatus' 			=> 'required',
		  'operation_billingaccountnumber' 	=> 'required',
		  'operation_updated_at' 			=> 'required',
		  'operation_activation_updatedat' 	=> 'required',
		  'operation_sellerid' 				=> 'required',
		  'operation_subscriberid' 			=> 'required',
		  'operation_status' 				=> 'required',
		  'operation_activationid' 			=> 'required',
		  'operation_activationstatusid' 	=> 'required',
		  'operation_activationdate' 		=> 'required',
		  'operation_imei' 					=> 'required',
		  'operation_iccid' 				=> 'required',
		  'operation_subscribername' 		=> 'required',
		  'operation_size' 					=> 'required',
		  'operation_mvno' 					=> 'required',
		  'operation_activationtype' 		=> 'required',
		]);
	 	if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
        $data = array(
            'operation_id' 						=> $request->operation_id,
            'operation_phonenumber'				=> $request->operation_phonenumber,
			'operation_portinstatus' 			=> $request->operation_portinstatus,
			'operation_billingaccountnumber' 	=> $request->operation_billingaccountnumber,
			'operation_updated_at' 				=> $request->operation_updated_at,
			'operation_activation_updatedat' 	=> $request->operation_activation_updatedat,
			'operation_sellerid' 				=> $request->operation_sellerid,
			'operation_subscriberid' 			=> $request->operation_subscriberid,
			'operation_status' 					=> $request->operation_status,
			'operation_activationid' 			=> $request->operation_activationid,
			'operation_activationstatusid' 		=> $request->operation_activationstatusid,
			'operation_activationdate' 			=> $request->operation_activationdate,
			'operation_imei' 					=> $request->operation_imei,
			'operation_iccid' 					=> $request->operation_iccid,
			'operation_subscribername' 			=> $request->operation_subscribername,
			'operation_size' 					=> $request->operation_size,
			'operation_mvno' 					=> $request->operation_mvno,
			'operation_activationtype' 			=> $request->operation_activationtype,
			'updated_by'						=> $request->user_id,
			'updated_at'						=> date('Y-m-d h:i:s'),
        );
		$update  = DB::table('operation')
		->where('operation_mainid','=',$request->operation_mainid)
		->update($data);
		if($updatesubscriber){
			return response()->json(['message' => 'Operation Updated Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function operationlist(Request $request){
		$list = DB::table('operation')
		->select('*','operation_mainid as id')
		->where('status_id','=',1)
		->orderBy('operation_id','DESC')
		->get();
		if(isset($list)){
			return response()->json(['data' => $list,'message' => 'Operation List'],200);
		}else{
			return response()->json(['data' => $emptyarray, 'message' => 'Operation List'],200);
		}
	}
	public function operationdetail(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'operation_mainid'	=> 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json("Operation Main Id Required", 400);
		}
		$data = DB::table('operation')
		->select('*')
		->where('operation_mainid','=',$request->operation_mainid)
		->where('status_id','=',1)
		->first();
		if($data){
			return response()->json(['data' => $data, 'message' => 'Operation Details'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function deleteoperation(Request $request){
		$validate = Validator::make($request->all(), [
	      'operation_mainid'	=> 'required',
	    ]);
     	if ($validate->fails()) {  
			return response()->json("Operation Main Id Required", 400);
		}
		$update  = DB::table('operation')
		->where('operation_mainid','=',$request->operation_mainid)
		->update([
			'status_id' 	=> 2,
			'deleted_by'	=> $request->user_id,
			'deleted_at'	=> date('Y-m-d h:i:s'),
		]);
		if($update){
			return response()->json(['message' => 'Operation Deleted Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function saveoperation(Request $request){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/operations/list?MVNO=500087&offset=0&limit=20',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			'Authorization: Basic cHJhdHRfbW9iaWxlOlByQDFrJUIj'
		),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response);
		foreach($response->results as $results){
			$personal = DB::table('operation')
			->select('*')
			->where('operation_id','=',$results->operation_id)
			->where('status_id','=',1)
			->count();
			if($personal == 0){
				if(isset($results->activationStatusId)){
					$activationstatusid = $results->activationStatusId;
				}else{
					$activationstatusid = "";
				}
				if(isset($results->size)){
					$size = $results->size;
				}else{
					$size = "";
				}
				$adds = array(
					'operation_id' 						=> $results->operation_id,
					'operation_phonenumber' 			=> $results->phoneNumber,
					'operation_portinstatus' 			=> $results->portinStatus,
					'operation_billingaccountnumber' 	=> $results->billingAccountNumber,
					'operation_updated_at' 				=> $results->updated_at,
					'operation_activation_updated_at' 	=> $results->activation_updated_at,
					'operation_sellerid' 				=> $results->sellerId,
					'operation_subscriberid' 			=> $results->subscriberId,
					'operation_status' 					=> $results->status,
					'operation_activationid' 			=> $results->activationId,
					'operation_activationstatusid' 		=> $activationstatusid,
					'operation_activationdate' 			=> $results->activationDate,
					'operation_imei' 					=> $results->imei,
					'operation_iccid' 					=> $results->iccid,
					'operation_subscribername' 			=> $results->subscriberName,
					'operation_size' 					=> $size,
					'operation_mvno' 					=> $results->mvno,
					'operation_activationtype' 			=> $results->activation_type,
					'status_id'							=> 1,
					'created_by'						=> $request->user_id,
					'created_at'						=> date('Y-m-d h:i:s')
				);
				$save = DB::table('operation')->insert($adds);
			}
		}
		if(isset($save)){
			return response()->json(['message' => 'Saved Successfully'],200);
		}else{
			return response()->json(['message' => 'Already Saved'],200);
		}
	}
}