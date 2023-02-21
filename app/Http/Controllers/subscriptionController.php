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

class subscriptionController extends Controller
{
	public $emptyarray = array();
	public function createsubscription(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'subscription_phonenumber' 			=> 'required',
          'subscription_status' 				=> 'required',
		  'subscription_billingaccountnumber' 	=> 'required',
		  'subscription_activationdate' 		=> 'required',
		  'subscription_imei' 					=> 'required',
		  'subscription_iccid' 					=> 'required',
		  'subscription_subscribername' 		=> 'required',
		  'subscription_size' 					=> 'required',
		  'subscription_planname' 				=> 'required',
		  'subscription_servicetype' 			=> 'required',
		  'subscription_usage' 					=> 'required',
        ]);
     	if ($validate->fails()) {
			return response()->json($validate->errors(), 400);
		}
		$adds = array(
			'subscription_phonenumber' 			=> $request->subscription_phonenumber,
			'subscription_status' 				=> $request->subscription_status,
			'subscription_billingaccountnumber' => $request->subscription_billingaccountnumber,
			'subscription_activationdate' 		=> $request->subscription_activationdate,
			'subscription_imei' 				=> $request->subscription_imei,
			'subscription_iccid' 				=> $request->subscription_iccid,
			'subscription_subscribername' 		=> $request->subscription_subscribername,
			'subscription_size' 				=> $request->subscription_size,
			'subscription_planname' 			=> $request->subscription_planname,
			'subscription_servicetype' 			=> $request->subscription_servicetype,
			'subscription_usage' 				=> $request->subscription_usage,
			'status_id'							=> 1,
			'created_by'						=> $request->user_id,
			'created_at'						=> date('Y-m-d h:i:s')
		);
		$save = DB::table('subscription')->insert($adds);
		if($save){
			return response()->json(['message' => 'Subscription Created Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function updatesubscription(Request $request){
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
			return response()->json(['message' => 'Subscription Updated Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function subscriptionlist(Request $request){
		$list = DB::table('subscription')
		->select('*','subscription_id as id')
		->where('status_id','=',1)
		->orderBy('subscription_id','DESC')
		->get();
		if(isset($list)){
			return response()->json(['data' => $list,'message' => 'Subscription List'],200);
		}else{
			return response()->json(['data' => $emptyarray, 'message' => 'Subscription List'],200);
		}
	}
	public function subscriptiondetails(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'subscription_mainid'	=> 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json("Subscription Main Id Required", 400);
		}
		$data = DB::table('subscription')
		->select('*')
		->where('subscription_id','=',$request->subscription_mainid)
		->where('status_id','=',1)
		->first();
		if($data){
			return response()->json(['data' => $data, 'message' => 'Subscription Details'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function deletesubscription(Request $request){
		$validate = Validator::make($request->all(), [
	      'subscription_mainid'	=> 'required',
	    ]);
     	if ($validate->fails()) {
			return response()->json("Subscription Main Id Required", 400);
		}
		$update  = DB::table('subscription')
		->where('subscription_id','=',$request->subscription_mainid)
		->update([
			'status_id' 	=> 2,
			'deleted_by'	=> $request->user_id,
			'deleted_at'	=> date('Y-m-d h:i:s'),
		]);
		if($update){
			return response()->json(['message' => 'Subscription Deleted Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function savesubscription(Request $request){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/services/list?MVNO=500087&offset=0&limit=20',
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
			$personal = DB::table('subscription')
			->select('*')
			->where('subscription_imei','=',$results->imei)
			->where('status_id','=',1)
			->count();
			if($personal == 0){
				$adds = array(
					'subscription_phonenumber' 			=> $results->phoneNumber,
					'subscription_status' 				=> $results->status,
					'subscription_billingaccountnumber' => $results->billingAccountNumber,
					'subscription_activationdate' 		=> $results->activationDate,
					'subscription_imei' 				=> $results->imei,
					'subscription_iccid' 				=> $results->iccid,
					'subscription_subscribername' 		=> $results->subscriberName,
					'subscription_size' 				=> $results->size,
					'subscription_planname' 			=> $results->planName,
					'subscription_servicetype' 			=> $results->serviceType,
					'subscription_usage' 				=> $results->usage,
					'status_id'							=> 1,
					'created_by'						=> $request->user_id,
					'created_at'						=> date('Y-m-d h:i:s')
				);
				$save = DB::table('subscription')->insert($adds);
			}
		}
		if($save){
			return response()->json(['message' => 'Saved Successfully'],200);
		}else{
			return response()->json(['message' => 'Already Saved'],200);
		}
	}
	public function verifyimei(Request $request){
		$validate = Validator::make($request->all(), [
	      	'imei_number'	=> 'required',
	    ]);
     	if ($validate->fails()) {
			return response()->json("IMEI Number Is Required", 400);
		}
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/devices/351675643571544?MVNO=500087',
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
        if($response){
			return response()->json(['data' => $response],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function subscrptionwithoutportin(Request $request){
		$validate = Validator::make($request->all(), [
	      	'activations_mainid'	=> 'required',
	    ]);
     	if ($validate->fails()) {
			return response()->json("Activation Main Id Is Required", 400);
		}
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/activations?MVNO=500087',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>'{
			"callbackUrl": "https://mobility.pareteum.api/WebHook/getPortinActivationResponse",
			"activations": [
				{
					"subscriber": {
						"address": {
							"streetAddress": "2900 W Plano Pkwy",
							"addressLine2": "string",
							"city": "Plano",
							"state": "TX",
							"zipCode": "75075"
						},
						"firstName": "Pareteum",
						"lastName": "Test",
						"email": "pareteum.test@mailinator.com",
						"contactNumber": "9849599599"
					},
					"service": {
						"characteristics": [
							{
								"name": "serviceZipCode",
								"value": "75075"
							},
							{
								"name": "IMEI",
								"value": "352498092175504"
							},
							{
								"name": "ICCID",
								"value": "89014103272368891806"
							},
							{
								"name": "size",
								"value": "100MB"
							},
							{
								"name": "planName",
								"value": "Mobility Basic Phone"
							},
							{
								"name": "tethering",
								"value": "NO"
							},
							{
								"name": "serviceType",
								"value": "Data And Voice"
							},
							{
								"name": "intlCalling",
								"value": "No"
							},
							{
								"name": "intlRoaming",
								"value": "No"
							}                  
						]
					}
				}
			]
		}',
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: Basic cHJhdHRfbW9iaWxlOlByQDFrJUIj'
		),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response);
        if($response){
			return response()->json(['data' => $response],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function subscrptionwithportin(Request $request){
		$validate = Validator::make($request->all(), [
	      	'activations_mainid'	=> 'required',
	    ]);
     	if ($validate->fails()) {
			return response()->json("Activation Main Id Is Required", 400);
		}
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/activations?MVNO=500087',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>'{
			"activations": [
				{
					"subscriber": {
						"subscriberId": 4,
						"firstName": "Pareteum",
						"lastName": "Test",
						"email": "pareteum.test@mailinator.com",
						"contactNumber": "9546699999",
						"address": {
							"streetAddress": "180 S 100 W",
							"addressLine2": "",
							"city": "PLEASANT GRV",
							"state": "UT",
							"zipCode": "84062"
						}
					},
					"service": {
						"phoneNumber": "5109826326",
						"characteristics": [
							{
								"name": "serviceZipCode",
								"value": "94566"
							},
							{
								"name": "IMEI",
								"value": "355800070505858"
							},
							{
								"name": "ICCID",
								"value": "89014103272368891806"
							},
							{
								"name": "planName",
								"value": "Mobile Select"
							},
							{
								"name": "size",
								"value": "1GB"
							},
							{
								"name": "tethering",
								"value": "Yes"
							},
							{
								"name": "serviceType",
								"value": "Data and Voice"
							},
							{
								"name": "intlCalling",
								"value": "Yes"
							},
							{
								"name": "intlRoaming",
								"value": "Yes"
							}
						]
					},
					"portInInfo": {
						"phoneNumber": "5109826326",
						"portRequestLineId": "5109826326",
						"serviceZipCode": "84062",
						"subscriber": {
							"firstName": "Laxman",
							"lastName": "Battini",
							"address": {
								"streetAddress": "180 S 100 W",
								"addressLine2": "",
								"city": "PLEASANT GRV",
								"state": "UT",
								"zipCode": "84062"
							}
						},
						"authorizer": {
							"firstName": "Laxman",
							"lastName": "Battini",
							"address": {
								"streetAddress": "180 S 100 W",
								"addressLine2": "",
								"city": "PLEASANT GRV",
								"state": "UT",
								"zipCode": "84062"
							}
						},
						"serviceInfo": {
							"area": "005015001430"
						},
						"oldServiceProvider": {
							"accountNumber": "12345",
							"password": "123",
							"firstName": "Pareteum",
							"lastName": "Test"
						}
					}
				}
			]
		}',
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
			'Authorization: Basic cHJhdHRfbW9iaWxlOlByQDFrJUIj'
		),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response);
        if($response){
			return response()->json(['data' => $response],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function subscrptionstatus(Request $request){
		$validate = Validator::make($request->all(), [
	      	'operation_id'	=> 'required',
	    ]);
     	if ($validate->fails()) {
			return response()->json("Operation Id Is Required", 400);
		}
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/operations/10?MVNO=500087',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			'app-id: fd3bb4429ae44a86b8ed8f40f9940239',
			'app-secret: 1280d88ae4EF458786E45937f78eF654',
			'Content-Type: application/json',
			'Authorization: Basic cHJhdHRfbW9iaWxlOlByQDFrJUIj'
		),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response);
        if($response){
			return response()->json(['data' => $response],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
}