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

class sellermanagementController extends Controller
{
	public $emptyarray = array();
	public function createseller(Request $request){
        $validate = Validator::make($request->all(), [ 
            'seller_firstName' 	       => 'required',
            'seller_lastName' 	       => 'required',
            'seller_email' 	           => 'required',
            'seller_phonenumber' 	   => 'required',
            'seller_role'              => 'required',
            'seller_additionaldata'    => 'required',
            'seller_resellername'      => 'required',
            'seller_streetAddress'     => 'required',
            'seller_city'              => 'required',
            'seller_state'             => 'required',
            'seller_zipcode'           => 'required',
        ]);
        if ($validate->fails()) {    
              return response()->json($validate->errors(), 400);
        }
		$curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/sellers?MVNO=500087',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "firstName": "Rang",
            "lastName": "Laxman",
            "email": "testemail@lax.com",
            "phoneNumber": "12343456789",
            "role": "Admin",
            "additionalData": "string",
            "resellerName": "Rang Bare",
            "address": {
                "streetAddress": "1194 CONCORD ST",
                "city": "Pleasanton",
                "state": "CA",
                "zipCode": "94566"
            }
        }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic cHJhdHRfbW9iaWxlOlByQDFrJUIj',
            'Content-Type: application/json'
        ),
        ));
        $response = curl_exec($curl);
        $response = json_decode($response);
        curl_close($curl);
          $adds = array(
          'seller_id' 	            => $response->seller_id,
          'seller_firstName' 	    => $request->firstName,
          'seller_lastName'		    => $request->lastName,
          'seller_email' 		    => $request->email,
          'seller_phonenumber'      => $request->phoneNumber,
          'seller_role'             => $request->role,
          'seller_additionaldata'   => $request->additionalData,
          'seller_resellername'     => $request->resellerName,
          'seller_streetAddress'    => $request->streetAddress,
          'seller_city'             => $request->city,
          'seller_state'            => $request->state,
          'seller_zipcode'          => $request->zipcode,
          'status_id'		 	    => 1,
          'created_by'	 	        => $request->user_id,
          'created_at'	 	        => date('Y-m-d h:i:s'),
          );
          $save = DB::table('seller')->insert($adds);
		if(isset($response)){
			return response()->json(['message' => $response->messages],200);
		}else{
			return response()->json(['message' => 'Oops! something went wrong'],400);
		}
	}
    public function sellerlist(Request $request){
		$data = DB::table('seller')
		->select('*')
		->where('status_id','=',1)
		->get();
		if($data){
			return response()->json(['data' => $data, 'message' => 'Seller List'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function sellerdetails(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'seller_mainid'	=> 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json("Seller Main Id Required", 400);
		}
		$details = DB::table('seller')
		->select('*')
		->where('seller_mainid','=',$request->seller_mainid)
		->where('status_id','=',1)
		->first();
		if($details){
			return response()->json(['data' => $details, 'message' => 'Seller Details'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function deleteseller(Request $request){
		$validate = Validator::make($request->all(), [
	      'seller_id'	=> 'required',
	    ]);
     	if ($validate->fails()) {
			return response()->json("Seller Id Required", 400);
		}
		$update  = DB::table('seller')
		->where('seller_id','=',$request->seller_id)
		->update([
			'status_id' 	=> 2,
			'deleted_by'	=> $request->user_id,
			'deleted_at'	=> date('Y-m-d h:i:s'),
		]);
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/sellers/1?MVNO=500087',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'DELETE',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic cHJhdHRfbW9iaWxlOlByQDFrJUIj'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
		if($update){
			return response()->json(['message' => 'Seller Deleted Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
}