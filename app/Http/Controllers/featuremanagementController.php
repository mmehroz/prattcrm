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

class featuremanagementController extends Controller
{
	public $emptyarray = array();
	public function internationalcalling(Request $request){
        $validate = Validator::make($request->all(), [ 
            'phonenumber' 	=> 'required',
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/services/9253534568?MVNO=500087',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS =>'{
            "characteristics":[
                {
                "name": "intlCalling",
                "value": "No"
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
			return response()->json(['message' => $response->messages],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
    public function internationalroaming(Request $request){
        $validate = Validator::make($request->all(), [ 
            'phonenumber' 	=> 'required',
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/services/9253534568?MVNO=500087',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS =>'{
            "characteristics":[
                {
                "name": "intlRoaming",
                "value": "Yes"
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
            return response()->json(['message' => $response->messages],200);
        }else{
            return response()->json(['message' => $response->messages],200);
        }
    }
    public function internationaldayplan(Request $request){
        $validate = Validator::make($request->all(), [ 
            'phonenumber' 	=> 'required',
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/services/9253534568?MVNO=500087',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS =>'{
            "features":[
                {
                "name": "FTRS Intl Day Pass",
                "value": "No"
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
            return response()->json(['message' => $response->messages],200);
        }else{
            return response()->json(['message' => $response->messages],200);
        }
    }
    public function datablocking(Request $request){
        $validate = Validator::make($request->all(), [ 
            'phonenumber' 	=> 'required',
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/services/9253534568?MVNO=500087',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS =>'{
            "features":[
                {
                "name": "FTRS Data Blocking",
                "value": "Yes"
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
            return response()->json(['message' => $response->messages],200);
        }else{
            return response()->json(['message' => $response->messages],200);
        }
    }
    public function outboundcalleridblocking(Request $request){
        $validate = Validator::make($request->all(), [ 
            'phonenumber' 	=> 'required',
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/services/9253534568?MVNO=500087',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS =>'{
            "features":[
                {
                "name": "FTRS Outbound CallerID Blocking",
                "value": "No"
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
            return response()->json(['message' => $response->messages],200);
        }else{
            return response()->json(['message' => $response->messages],200);
        }
    }
    public function worldconnectadvantage(Request $request){
        $validate = Validator::make($request->all(), [ 
            'phonenumber' 	=> 'required',
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/services/9253534568?MVNO=500087',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS =>'{
            "features":[
                {
                "name": "FTRS World Connect Advantage",
                "value": "No"
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
            return response()->json(['message' => $response->messages],200);
        }else{
            return response()->json(['message' => $response->messages],200);
        }
    }
    public function suppressmessaging(Request $request){
        $validate = Validator::make($request->all(), [ 
            'phonenumber' 	=> 'required',
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/services/9253534568?MVNO=500087',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS =>'{
            "features":[
                {
                "name": "FTRS Suppress Messaging",
                "value": "YES"
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
            return response()->json(['message' => $response->messages],200);
        }else{
            return response()->json(['message' => $response->messages],200);
        }
    }
    public function passportvoicesmsdata(Request $request){
        $validate = Validator::make($request->all(), [ 
            'phonenumber' 	=> 'required',
        ]);
        if ($validate->fails()) {    
            return response()->json($validate->errors(), 400);
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/services/9253534568?MVNO=500087',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS =>'{
            "features":[
                {
                "name": "FTRS Passport Voice, SMS and Data",
                "value": "Yes"
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
            return response()->json(['message' => $response->messages],200);
        }else{
            return response()->json(['message' => $response->messages],200);
        }
    }
    public function passportdata(Request $request){
        $validate = Validator::make($request->all(), [ 
            'phonenumber' 	=> 'required',
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://prod.mobility-api.pareteum.cloud/v3/mobility/services/9253534568?MVNO=500087',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS =>'{
            "features":[
                {
                "name": "FTRS Passport Data only",
                "value": "No"
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
            return response()->json(['message' => $response->messages],200);
        }else{
            return response()->json(['message' => $response->messages],200);
        }
    }
}