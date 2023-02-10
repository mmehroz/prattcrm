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
	public function changeimei(Request $request){
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
            "characteristics": [
                {
                    "name": "IMEI",
                    "value": "352498092175504"
                },
                {
                    "name": "serviceZipCode",
                    "value": "94566"
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
    public function changesim(Request $request){
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
                "name": "SIM",
                "value": "89014104270225985364"
                },
                {
                "name": "serviceZipCode",
                "value": "33076"
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