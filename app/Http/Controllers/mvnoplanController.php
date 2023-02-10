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

class mvnoplanController extends Controller
{
	public $emptyarray = array();
	public function createmvnoplan(Request $request){
		$validate = Validator::make($request->all(), [ 
	        'mvnoplan_title' 		=> 'required',
            'mvnoplan_tethering' 	=> 'required',
		    'mvnoplan_type'         => 'required',
            'mvoplan_size'          => 'required',
            'mvnodefault'      		=> 'required',
            'mvnointlcalling'  		=> 'required',
            'mvnointlroaming'  		=> 'required',
        ]);
     	if ($validate->fails()) {
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'mvnoplan_title' 		=> $request->mvnoplan_title,
			'mvnoplan_tethering' 	=> $request->mvnoplan_tethering,
			'mvnoplan_type'         => $request->mvnoplan_type,
			'mvnoplan_size' 	    => $request->mvnoplan_size,
			'status_id'             => 1,
            'created_by'            => $request->user_id,
            'created_at'            => date('Y-m-d h:i:s'),
		);
		$save = DB::table('mvnoplan')->insert($adds);
		$mvnoplan_mainid - DB::getPdo()->lastInsertId();
		if(isset($request->mvnodefault)){
			foreach($request->mvnodefault as $defaults){
				$mvnodefault = array(
					'mvnodefault_intlcalling' 			=> $defaults['intlcalling'],
					'mvnodefault_roaming'				=> $defaults['roaming'],
					'mvnodefault_messagesuppression' 	=> $defaults['messagesppression'],
					'mvnodefault_callernameblocking'	=> $defaults['callernameblocking'],
				);
				DB::table('mvnodefault')->insert($mvnodefault);
			}
		}
		if(isset($request->mvnointlcalling)){
			foreach($request->mvnointlcalling as $intlcallings){
				$mvnointlcalling = array(
					'mvnointlcalling_calling' 	=> 	$intlcallings['calling'],
					'mvnoplan_mainid'			=>	$mvnoplan_mainid,
				);
				DB::table('mvnointlcalling')->insert($mvnointlcalling);
			}
		}
		if(isset($request->mvnointlroaming)){
			foreach($request->mmvnointlroaming as $intlroaming){
				$mvnointlroaming = array(
					'mvnointlroaming_roaming' 	=> 	$intlroaming['roaming'],
					'mvnoplan_mainid'			=>	$mvnoplan_mainid,
				);
				DB::table('mvnointlroaming')->insert($mvnointlroaming);
			}
		}
		if($save){
			return response()->json(['message' => 'MVNO Plan Created Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function updatemvnoplan(Request $request){
		$validate = Validator::make($request->all(), [ 
			'subscription_phonenumber' 			=> 'required',
			'subscription_status'				=> 'required',
			'subscription_billingaccountnumber' => 'required',
			'subscription_activationdate' 		=> 'required',
			'subscription_imei' 				=> 'required',
			'subscription_iccid' 				=> 'required',
			'subscription_subscribername' 		=> 'required',
			'subscription_size' 				=> 'required',
			'subscription_planname' 			=> 'required',
			'subscription_servicetype' 			=> 'required',
			'subscription_usage' 				=> 'required',
		]);
	 	if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
        $data = array(
           	'subscription_phonenumber' 			=> $request->subscription_phonenumber,
		   	'subscription_status' 				=> $request->subscription_status,
		   	'subscription_billingaccountnumber' => $request->susbcription_billingaccountnumber,
		   	'subscription_activationdate' 		=> $request->subscription_activationdate,
		    'subscription_imei' 				=> $request->subscription_imei,
			'subscription_iccid' 				=> $request->subscription_iccid,
			'subscription_susbcribername' 		=> $request->subscription_subscribername,
			'subscription_size' 				=> $request->subscription_size,
			'subscription_planname' 			=> $request->subscription_planname,
			'subscription_servicetype' 			=> $request->subscription_servicetype,
			'subscription_usage' 				=> $request->subscription_usage,
        );
		$update  = DB::table('subscription')
		->where('subscription_mainid','=',$request->operation_mainid)
		->update($data);
		if($updatesubscriber){
			return response()->json(['message' => 'MVNO Plan Updated Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function mvnoplanlist(Request $request){
		$list = DB::table('mvnoplan')
		->select('*')
		->where('status_id','=',1)
		->orderBy('mvnoplan_id','DESC')
		->get();
		if(isset($list)){
			return response()->json(['data' => $list,'message' => 'MVNO Plan List'],200);
		}else{
			return response()->json(['data' => $emptyarray, 'message' => 'MVNO Plan List'],200);
		}
	}
	public function mvnoplandetails(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'mvnoplan_id'	=> 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json("Subscription Main Id Required", 400);
		}
		$data = DB::table('susbcription')
		->select('*')
		->where('mvnoplan_id','=',$request->mvnoplan_id)
		->where('status_id','=',1)
		->first();
		if($data){
			return response()->json(['data' => $data, 'message' => 'MVNO Plan Details'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function deletemvnoplan(Request $request){
		$validate = Validator::make($request->all(), [
	      'mvnoplan_id'	=> 'required',
	    ]);
     	if ($validate->fails()) {
			return response()->json("MVNO Plan Id Required", 400);
		}
		$update  = DB::table('mvnoplan')
		->where('mvnoplan_id','=',$request->mvnoplan_id)
		->update([
			'status_id' 	=> 2,
			'deleted_by'	=> $request->user_id,
			'deleted_at'	=> date('Y-m-d h:i:s'),
		]);
		if($update){
			return response()->json(['message' => 'MVNO Plan Deleted Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function savemvnoplan(Request $request){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/plans?MVNO=500087',
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
		foreach($response->plans as $results){
			$adds = array(
				'mvnoplan_title' 		=> $results->plan,
				'mvnoplan_tethering' 	=> $results->tethering,
				'mvnoplan_type' 		=> $results->type,
				'mvoplan_size' 			=> $results->size,
				'status_id'				=> 1,
				'created_by'			=> $request->user_id,
				'created_at'			=> date('Y-m-d h:i:s')
			);
			$save = DB::table('operation')->insert($adds);
			$mvnoplan_id - DB::getPdo()->lastInsertId();
			if(isset($results->defaults)){
				foreach($results->defaults as $defaults){
					$mvnodefault = array(
						'mvnodefault_intlcalling' 			=> $defaults->intlCalling,
						'mvnodefault_roaming'				=> $defaults->intlRoaming,
						'mvnodefault_messagesuppression' 	=> $defaults->MessageSuppression,
						'mvnodefault_callernameblocking'	=> $defaults->CallerNameBlocking,
						'mvnoplan_id'						=> $mvnoplan_id,
						'status_id'							=> 1,
						'created_by'						=> $request->user_id,
						'created_at'						=> date('Y-m-d h:i:s')
					);
					DB::table('mvnodefault')->insert($mvnodefault);
				}
			}
		}
		if(isset($results->intlCalling)){
			foreach($results->intlCalling as $intlcallings){
				$mvnointlcalling = array(
					'mvnointlcalling_calling' 	=> $intlcallings,
					'mvnoplan_id'				=> $mvnoplan_id,
					'status_id'					=> 1,
					'created_by'				=> $request->user_id,
					'created_at'				=> date('Y-m-d h:i:s')
				);
				DB::table('mvnointlcalling')->insert($mvnointlcalling);
			}
		}
		if(isset($result->intlRoaming)){
			foreach($result->mintlRoaming as $intlRoaming){
				$mvnointlroaming = array(
					'mvnointlroaming_roaming' 	=> $intlroaming,
					'mvnoplan_id'				=> $mvnoplan_id,
					'status_id'					=> 1,
					'created_by'				=> $request->user_id,
					'created_at'				=> date('Y-m-d h:i:s')
				);
				DB::table('mvnointlroaming')->insert($mvnointlroaming);
			}
		}
		if(isset($save)){
			return response()->json(['message' => 'Saved Successfully'],200);
		}else{
			return response()->json(['message' => 'Oops! Something Went Wrong'],400);
		}
	}
}