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

class imeiplanController extends Controller
{
	public $emptyarray = array();
	public function imeiplanlist(Request $request){
		$list = DB::table('imeiplan')
		->select('*','imeiplan_id as id')
		->where('status_id','=',1)
		->orderBy('imeiplan_id','DESC')
		->get();
		if(isset($list)){
			return response()->json(['data' => $list,'message' => 'IMEI Plan List'],200);
		}else{
			return response()->json(['data' => $emptyarray, 'message' => 'IMEI Plan List'],200);
		}
	}
	public function imeiplandetails(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'imeiplan_id'	=> 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json("IMEI Plan Id Required", 400);
		}
		$basic = DB::table('imeiplan')
		->select('*', 'imeiplan_id as id')
		->where('imeiplan_id','=',$request->imeiplan_id)
		->where('status_id','=',1)
		->first();
		$default = DB::table('imeidefault')
		->select('*','imeidefault_id as id')
		->where('imeiplan_id','=',$request->imeiplan_id)
		->where('status_id','=',1)
		->get();
		$calling = DB::table('imeiintlcalling')
		->select('*','imeiintlcalling_id as id')
		->where('imeiplan_id','=',$request->imeiplan_id)
		->where('status_id','=',1)
		->get();
		$roaming = DB::table('imeiintlroaming')
		->select('*','imeiintlroaming_id as id')
		->where('imeiplan_id','=',$request->imeiplan_id)
		->where('status_id','=',1)
		->get();
		if($basic){
			return response()->json(['basic' => $basic, 'default' => $default, 'calling' => $calling, 'roaming' => $roaming, 'message' => 'IMEI Plan Details'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function deleteimeiplan(Request $request){
		$validate = Validator::make($request->all(), [
	      'imeiplan_id'	=> 'required',
	    ]);
     	if ($validate->fails()) {
			return response()->json("IMEI Plan Id Required", 400);
		}
		$update  = DB::table('imeiplan')
		->where('imeiplan_id','=',$request->imeiplan_id)
		->update([
			'status_id' 	=> 2,
			'deleted_by'	=> $request->user_id,
			'deleted_at'	=> date('Y-m-d h:i:s'),
		]);
		if($update){
			return response()->json(['message' => 'IMEI Plan Deleted Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function saveimeiplan(Request $request){
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
				'imeiplan_title' 		=> $results->plan,
				'imeiplan_tethering' 	=> $results->tethering,
				'imeiplan_type' 		=> $results->type,
				'imeiplan_size' 		=> $results->size,
				'status_id'				=> 1,
				'created_by'			=> $request->user_id,
				'created_at'			=> date('Y-m-d h:i:s')
			);
			$save = DB::table('imeiplan')->insert($adds);
			$imeiplan_id = DB::getPdo()->lastInsertId();
			if(isset($results->defaults)){
				foreach($results->defaults as $defaults){
					if(isset($defaults->intlCalling)){
						$calling = $defaults->intlCalling;
					}else{
						$calling = "-";
					}
					if(isset($defaults->intlRoaming)){
						$roaming = $defaults->intlRoaming;
					}else{
						$roaming = "-";
					}
					if(isset($defaults->MessageSuppression)){
						$suppression = $defaults->MessageSuppression;
					}else{
						$suppression = "-";
					}
					if(isset($defaults->CallerNameBlocking)){
						$blocking = $defaults->CallerNameBlocking;
					}else{
						$blocking = "-";
					}
					$imeidefault = array(
						'imeidefault_intlcalling' 			=> $calling,
						'imeidefault_roaming'				=> $roaming,
						'imeidefault_messagesuppression' 	=> $suppression,
						'imeidefault_callernameblocking'	=> $blocking,
						'imeiplan_id'						=> $imeiplan_id,
						'status_id'							=> 1,
						'created_by'						=> $request->user_id,
						'created_at'						=> date('Y-m-d h:i:s')
					);
					DB::table('imeidefault')->insert($imeidefault);
				}
			}
			if(isset($results->intlCalling)){
				foreach($results->intlCalling as $intlcallings){
					$imeiintlcalling = array(
						'imeiintlcalling_calling' 	=> $intlcallings,
						'imeiplan_id'				=> $imeiplan_id,
						'status_id'					=> 1,
						'created_by'				=> $request->user_id,
						'created_at'				=> date('Y-m-d h:i:s')
					);
					DB::table('imeiintlcalling')->insert($imeiintlcalling);
				}
			}
			if(isset($results->intlRoaming)){
				foreach($results->intlRoaming as $intlroaming){
					$imeiintlroaming = array(	
						'imeiintlroaming_roaming' 	=> $intlroaming,
						'imeiplan_id'				=> $imeiplan_id,
						'status_id'					=> 1,
						'created_by'				=> $request->user_id,
						'created_at'				=> date('Y-m-d h:i:s')
					);
					DB::table('imeiintlroaming')->insert($imeiintlroaming);
				}
			}
		}
		if(isset($save)){
			return response()->json(['message' => 'Saved Successfully'],200);
		}else{
			return response()->json(['message' => 'Oops! Something Went Wrong'],400);
		}
	}
}