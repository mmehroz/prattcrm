<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\settingsController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\vendorController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\subcategoryController;
use App\Http\Controllers\productController;
use App\Http\Controllers\subscriberController;
use App\Http\Controller\operationController;
use App\Http\Controller\subscriptionController;
/*
|---------------------------------------------------------------------	-----
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::middleware('cors')->group(function(){
Route::any('/login', [loginController::class, 'login']);
Route::middleware('login.check')->group(function(){	
Route::any('/logout', [loginController::class, 'logout']);

Route::any('/role', [settingsController::class, 'role']);

Route::any('/createvendor', [vendorController::class, 'createvendor']);
Route::any('/updatevendor', [vendorController::class, 'updatevendor']);
Route::any('/vendorlist', [vendorController::class, 'vendorlist']);
Route::any('/vendordetails', [vendorController::class, 'vendordetails']);
Route::any('/deletevendor', [vendorController::class, 'deletevendor']);

Route::any('/createcategory', [categoryController::class, 'createcategory']);
Route::any('/updatecategory', [categoryController::class, 'updatecategory']);
Route::any('/categorylist', [categoryController::class, 'categorylist']);
Route::any('/categorydetails', [categoryController::class, 'categorydetails']);
Route::any('/deletecategory', [categoryController::class, 'deletecategory']);

Route::any('/createsubcategory', [subcategoryController::class, 'createsubcategory']);
Route::any('/updatesubcategory', [subcategoryController::class, 'updatesubcategory']);
Route::any('/subcategorylist', [subcategoryController::class, 'subcategorylist']);
Route::any('/subcategorydetails', [subcategoryController::class, 'subcategorydetails']);
Route::any('/deletesubcategory', [subcategoryController::class, 'deletesubcategory']);

Route::any('/createproduct', [productController::class, 'createproduct']);
Route::any('/updateproduct', [productController::class, 'updateproduct']);
Route::any('/productlist', [productController::class, 'productlist']);
Route::any('/productdetails',[productController::class, 'productdetails']);
Route::any('/deleteproduct',[productController::class, 'deleteproduct']);

Route::any('/createsubscriber', [subscriberController::class, 'createsubscriber']);
Route::any('/updatesubscriber', [subscriberController::class, 'updatesubscriber']);
Route::any('/subscriberlist', [subscriberController::class, 'subscriberlist']);
Route::any('/subscriberdetails', [subscriberController::class, 'subscriberdetails']);
Route::any('/deletesubscriber', [subscriberController::class, 'deletesubscriber']);
Route::any('/savesubscriber', [subscriberController::class, 'savesubscriber']);

Route::any('/createoperation', [subscriberController::class, 'createoperation']);
Route::any('/updateoperation', [subscriberController::class, 'updateoperation']);
Route::any('/operationlist', [subscriberController::class, 'operationlist']);
Route::any('/operationdetail', [subscriberController::class, 'operationdetail']);
Route::any('/deleteoperation', [subscriberController::class, 'deleteoperation']);
Route::any('/saveoperation', [operationController::class, 'saveoperation']);

Route::any('/createsubscription', [subscriberController::class, 'createsubscription']);
Route::any('/updatesubscription', [subscriberController::class, 'updatesubscription']);
Route::any('/subscriptionlist', [subscriberController::class, 'subscriptionlist']);
Route::any('/subscriptiondetail', [subscriberController::class, 'subscriptiondetail']);
Route::any('/deletesubscription', [subscriberController::class, 'deletesubscription']);
Route::any('/savesubscription', [subscriberController::class, 'savesubscription']);

});
});