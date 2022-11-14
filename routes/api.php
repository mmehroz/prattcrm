<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\settingsController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\vendorController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\subcategoryController;
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
});
});