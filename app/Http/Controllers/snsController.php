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

class smsController extends Controller
{
	public $emptyarray = array();
	public function sendsms(Request $request){
        $validate = Validator::make($request->all(), [ 
            'phonenumber' 	=> 'required',
            'message' 	    => 'required',
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/message/sms/'.$request->phonenumber.'?MVNO=500087',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "sender": "Pratt CRM",
        "message": $request->message
        }',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic cHJhdHRfbW9iaWxlOlByQDFrJUIj',
            'Content-Type: application/json'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);
        if($response){
			return response()->json(['message' => $response->messages],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
}