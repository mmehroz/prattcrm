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
use URL;

class productController extends Controller
{
	public $emptyarray = array();
	public function createproduct(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'product_name' 			=> 'required',
		  'product_code' 			=> 'required',
		  'product_qty' 			=> 'required',
		  'product_purchaseprice' 	=> 'required',
		  'product_sellingprice' 	=> 'required',
		  'product_discountprice' 	=> 'required',
          'product_thumbnail' 		=> 'required',
		  'vendor_id' 				=> 'required',
		  'category_id' 			=> 'required',
		  'subcategory_id' 			=> 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json($validate->errors(), 400);
		}
		if( $request->product_thumbnail->isValid()){
			$number = rand(1,999);
			$numb = $number / 7 ;
			$name = "product_thumbnail";
			$extension = $request->product_thumbnail->extension();
			$product_thumbnail  = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
			$product_thumbnail = $request->product_thumbnail->move(public_path('product_thumbnail/'),$product_thumbnail);
			$img = Image::make($product_thumbnail)->resize(800,800, function($constraint) {
					$constraint->aspectRatio();
			});
			$img->save($product_thumbnail);
			$product_thumbnail = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
		}else{
			return response()->json("Invalid File", 400);
		}
        $adds = array(
		'product_name' 				=> $request->product_name,
		'product_slug'				=> str_slug($request->product_name),
		'product_code' 				=> $request->product_code,
		'product_qty' 				=> $request->product_qty,
		'product_purchaseprice' 	=> $request->product_purchaseprice,
		'product_sellingprice' 		=> $request->product_sellingprice,
		'product_discountprice' 	=> $request->product_discountprice,
		'product_shortdescription' 	=> $request->product_shortdescription,
		'product_longdescription' 	=> $request->product_longdescription,
		'product_additionalinfo' 	=> $request->product_additionalinfo,
		'product_thumbnail' 		=> $product_thumbnail,
		'product_date'		 		=> date('Y-m-d'),
		'vendor_id' 				=> $request->vendor_id,
		'category_id' 				=> $request->category_id,
		'subcategory_id' 			=> $request->subcategory_id,
		'status_id'		 			=> 1,
		'created_by'	 			=> $request->user_id,
		'created_at'	 			=> date('Y-m-d h:i:s'),
		);
		$save = DB::table('product')->insert($adds);
		$product_id = DB::getPdo()->lastInsertId();
		if (isset($request->productgallery_name)) {
			$productgallery_name = $request->productgallery_name;
			$index = 0 ;
			$filename = array();
			foreach($productgallery_name as $productgallery_names){
				$saveproductgallery = array();
				if($productgallery_names->isValid()){
					$number = rand(1,999);
					$numb = $number / 7 ;
					$foldername = $product_id;
					$filename = $numb.$productgallery_names->getClientOriginalName();
					$productgallery_names->move(public_path('product_gallery/'.$foldername),$filename);
					$saveproductgallery = array(
					'productgallery_name'	=> $filename,
					'product_id'			=> $product_id,
					'status_id' 			=> 1,
					);
					DB::table('productgallery')->insert($saveproductgallery);
				}else{
					return response()->json("Invalid File", 400);
				}
			}
		}
		if($save){
			return response()->json(['message' => 'Product Created Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function updateproduct(Request $request){
		$validate = Validator::make($request->all(), [ 
			'product_id' 				=> 'required',
			'product_name' 				=> 'required',
			'product_code' 				=> 'required',
			'product_qty' 				=> 'required',
			'product_purchaseprice' 	=> 'required',
			'product_sellingprice' 		=> 'required',
			'product_discountprice' 	=> 'required',
			'product_shortdescription' 	=> 'required',
			'product_longdescription' 	=> 'required',
			'product_additionalinfo' 	=> 'required',
			'product_thumbnail' 		=> 'required',
			'vendor_id' 				=> 'required',
			'category_id' 				=> 'required',
			'subcategory_id' 			=> 'required',
	    ]);
	 	if ($validate->fails()) {
			return response()->json($validate->errors(), 400);
		}
        $data = array(
        'product_name' 				=> $request->product_name,
		'product_slug'				=> str_slug($request->product_name),
		'product_code' 				=> $request->product_code,
		'product_qty' 				=> $request->product_qty,
		'product_purchaseprice' 	=> $request->product_purchaseprice,
		'product_sellingprice' 		=> $request->product_sellingprice,
		'product_discountprice' 	=> $request->product_discountprice,
		'product_shortdescription' 	=> $request->product_shortdescription,
		'product_longdescription' 	=> $request->product_longdescription,
		'product_additionalinfo' 	=> $request->product_additionalinfo,
		'vendor_id' 				=> $request->vendor_id,
		'category_id' 				=> $request->category_id,
		'subcategory_id' 			=> $request->subcategory_id,
        'updated_by'			 	=> $request->user_id,
        'updated_at'		 		=> date('Y-m-d h:i:s'),
         );
		$updateproduct  = DB::table('product')
		->where('product_id','=',$request->product_id)
		->update($data);
		if (isset($request->product_thumbnail)) {
			if( $request->product_thumbnail->isValid()){
				$number = rand(1,999);
				$numb = $number / 7 ;
				$name = "product_thumbnail";
				$extension = $request->product_thumbnail->extension();
				$product_thumbnail  = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
				$product_thumbnail = $request->product_thumbnail->move(public_path('product_thumbnail/'),$product_thumbnail);
				$img = Image::make($product_thumbnail)->resize(800,800, function($constraint) {
						$constraint->aspectRatio();
				});
				$img->save($product_thumbnail);
				$product_thumbnail = date('Y-m-d')."_".$numb."_".$name."_.".$extension;
				DB::table('product')
				->where('product_id','=',$request->product_id)
				->update(['product_thumbnail' => $product_thumbnail]);
			}else{
				return response()->json("Invalid File", 400);
			}
		}
		if (isset($request->productgallery_name)) {
			$productgallery_name = $request->productgallery_name;
			$index = 0 ;
			$filename = array();
			foreach($productgallery_name as $productgallery_names){
				$saveproductgallery = array();
				if($productgallery_names->isValid()){
					$number = rand(1,999);
					$numb = $number / 7 ;
					$foldername = $request->product_id;
					$filename = $numb.$productgallery_names->getClientOriginalName();
					$productgallery_names->move(public_path('product_gallery/'.$foldername),$filename);
					$saveproductgallery = array(
					'productgallery_name'	=> $filename,
					'product_id'			=> $request->product_id,
					'status_id' 			=> 1,
					);
					DB::table('productgallery')->insert($saveproductgallery);
				}else{
					return response()->json("Invalid File", 400);
				}
			}
		}
		if($updateproduct){
			return response()->json(['message' => 'Product Updated Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function productlist(Request $request){
		$productlist = DB::table('product')
		->select('*', 'product_id as id')
		->where('status_id','=',1)
		->orderBy('product_id','DESC')
		->get();
		$thumbnailpath = URL::to('/')."/public/product_thumbnail/";
		if(isset($productlist)){
			return response()->json(['data' => $productlist, 'thumbnailpath' => $thumbnailpath, 'message' => 'Product List'],200);
		}else{
			return response()->json(['data' => $emptyarray, 'message' => 'Product List'],200);
		}
	}
	public function productdetails(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'product_id'		=> 'required',
	    ]);
     	if ($validate->fails()) {    
			return response()->json("Product Id Required", 400);
		}
		$details = DB::table('productdetails')
		->select('*')
		->where('product_id','=',$request->product_id)
		->where('status_id','=',1)
		->first();
		$gallery = DB::table('productgallery')
		->select('productgallery_name')
		->where('product_id','=',$request->product_id)
		->where('status_id','=',1)
		->get();
		$thumbnailpath = URL::to('/')."/public/product_thumbnail/".$details->product_thumbnail;
		$gallerypath = URL::to('/')."/public/product_gallery/".$request->product_id.'/';
		if($details){
			return response()->json(['data' => $details, 'gallery' => $gallery, 'thumbnailpath' => $thumbnailpath, 'gallerypath' => $gallerypath, 'message' => 'Product Details'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
	public function deleteproduct(Request $request){
		$validate = Validator::make($request->all(), [ 
	      'product_id'	=> 'required',
	    ]);
     	if ($validate->fails()) {
			return response()->json("Product Id Required", 400);
		}
		$update  = DB::table('product')
		->where('product_id','=',$request->product_id)
		->update([
		'status_id' 	=> 2,
		'deleted_by'	=> $request->user_id,
		'deleted_at'	=> date('Y-m-d h:i:s'),
		]); 
		if($update){
			return response()->json(['message' => 'Product Deleted Successfully'],200);
		}else{
			return response()->json("Oops! Something Went Wrong", 400);
		}
	}
}