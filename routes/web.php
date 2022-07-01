<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Auth::routes();


Route::group(['middleware' => ['auth','admin']], function () {
//    if(Auth::check()){
//        if (Auth::user()->email != 'admin@laybull.com')
//        {
//            return "un authourized Pe
////        dd("hererson";
//        }
//    }");



    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('product','\App\Http\Controllers\ProductController');
    Route::resource('vendor','\App\Http\Controllers\ProductController');
    Route::resource('slider','\App\Http\Controllers\SliderController');
    Route::resource('category','\App\Http\Controllers\CategoryController');
    Route::resource('brand','\App\Http\Controllers\BrandController');

    Route::resource('notification','\App\Http\Controllers\NotificationController');
    Route::resource('discount','\App\Http\Controllers\DiscountController');


    Route::post('product-reject/{id}','\App\Http\Controllers\ProductController@rejectProduct')->name('product-reject');
    Route::get('product-approve/{id}','\App\Http\Controllers\ProductController@approveProduct')->name('product-approve');

    Route::get('vendor','\App\Http\Controllers\VendorController@index')->name('vendor');
    Route::get('vendor-verified/{id}','\App\Http\Controllers\VendorController@vendorVerified')->name('vendor-verified');
    Route::get('vendor-unverified/{id}','\App\Http\Controllers\VendorController@vendorUnverified')->name('vendor-unverified');
});
