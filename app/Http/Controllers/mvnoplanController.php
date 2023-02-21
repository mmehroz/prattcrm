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
	public function mvnoplanlist(Request $request){
		$list = DB::table('mvnoplan')
		->select('*', 'mvnoplan_id as id')
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
			return response()->json("MVNO Plan Id Required", 400);
		}
		$basic = DB::table('mvnoplan')
		->select('*','mvnoplan_id as id')
		->where('mvnoplan_id','=',$request->mvnoplan_id)
		->where('status_id','=',1)
		->first();
		$default = DB::table('mvnodefault')
		->select('*','mvnodefault_id as id')
		->where('mvnoplan_id','=',$request->mvnoplan_id)
		->where('status_id','=',1)
		->get();
		$calling = DB::table('mvnointlcalling')
		->select('*','mvnointlcalling_id as id')
		->where('mvnoplan_id','=',$request->mvnoplan_id)
		->where('status_id','=',1)
		->get();
		$roaming = DB::table('mvnointlroaming')
		->select('*', 'mvnointlroaming_id as id')
		->where('mvnoplan_id','=',$request->mvnoplan_id)
		->where('status_id','=',1)
		->get();
		if($basic){
			return response()->json(['basic' => $basic, 'default' => $default, 'calling' => $calling,'roaming' => $roaming, 'message' => 'MVNO Plan Details'],200);
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
			$save = DB::table('mvnoplan')->insert($adds);
			$mvnoplan_id = DB::getPdo()->lastInsertId();
			if(isset($results->defaults)){
				$mvnodefault = array(
					'mvnodefault_intlcalling' 			=> $results->defaults[0]->intlCalling,
					'mvnodefault_roaming'				=> $results->defaults[1]->intlRoaming,
					'mvnodefault_messagesuppression' 	=> $results->defaults[2]->MessageSuppression,
					'mvnodefault_callernameblocking'	=> $results->defaults[3]->CallerNameBlocking,
					'mvnoplan_id'						=> $mvnoplan_id,
					'status_id'							=> 1,
					'created_by'						=> $request->user_id,
					'created_at'						=> date('Y-m-d h:i:s')
				);
				DB::table('mvnodefault')->insert($mvnodefault);
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
			if(isset($results->intlRoaming)){
				foreach($results->intlRoaming as $intlRoaming){
					$mvnointlroaming = array(
						'mvnointlroaming_roaming' 	=> $intlRoaming,
						'mvnoplan_id'				=> $mvnoplan_id,
						'status_id'					=> 1,
						'created_by'				=> $request->user_id,
						'created_at'				=> date('Y-m-d h:i:s')
					);
					DB::table('mvnointlroaming')->insert($mvnointlroaming);
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