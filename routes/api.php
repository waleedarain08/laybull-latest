<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('currency', 'API\ProductController@currencyGet');
Route::get('slider','\App\Http\Controllers\SliderController@apiSlider');

Route::get('guest-home-product','GuestController@homeProduct');
Route::get('guest-product/{id}','GuestController@product');
Route::post('guest-product-all','GuestController@allProducts');
Route::get('searchProduct', 'API\ProductController@searchProduct');
Route::get('search-filter', 'API\ProductController@searchFilter');
Route::get('brand_category', 'API\ProductController@brandCategory');
Route::get('productsizes/{id}', 'API\ProductController@productsizes');
Route::resource('categories', 'API\CategoryController');
Route::middleware('auth:api')->group(function () {

    Route::post('product/{id}', 'API\ProductController@productUpdate');
    Route::resource('users', 'API\UserController');
    Route::resource('products', 'API\ProductController');
    Route::resource('brands', 'API\BrandController');
    Route::resource('productbids', 'API\ProductBidController');
    Route::resource('productfavs', 'API\ProductFavouriteController');
    Route::resource('orders', 'API\OrderController');
    Route::resource('notifications', 'API\NotificationController');
    Route::resource('feedbacks', 'API\FeedbackController');
    Route::resource('shipping-detail', 'ShippingDetailController');

    Route::get('homeproducts', 'API\ProductController@homeproducts')->name('homeproducts');

    Route::get('favouriteproducts', 'API\ProductController@favouriteproducts')->name('favouriteproducts');

//    payment api
    Route::post('payment-process', 'PaymentController@payment');


    Route::post('seller_account_details', 'API\UserController@post_seller_account_details');



    Route::get('beercard', 'API\OrderController@beercard')->name('beercard');
    Route::get('resetbeercard', 'API\OrderController@resetbeercard')->name('resetbeercard');

    Route::get('about', 'API\UserController@about')->name('users.about');
    Route::post('changepassword', 'API\UserController@changepassword')->name('users.changepassword');
    Route::get('myfavs', 'UserController@myfavs')->name('users.myfavs');
    Route::get('mygoings', 'UserController@mygoings')->name('users.mygoings');
    Route::get('mylikes', 'UserController@mylikes')->name('users.mylikes');
    Route::post('notificationtoggle', 'API\UserController@notificationtoggle')->name('users.notificationtoggle');

    Route::post('profilepicture', 'API\UserController@profilepicture')->name('users.profilepicture');
    Route::get('email/resend', 'API\UserController@resend')->name('email.resend');

    Route::get('getSendOffersList', 'OfferController@offers');
    Route::get('getReceivedOffers', 'OfferController@collectOffers');

    Route::get('accept-offer/{id}','OfferController@acceptOffer');
    Route::get('reject-offer/{id}','OfferController@rejectOffer');
    Route::delete('delete-bit/{productBid}','OfferController@deleteBit');


    Route::post('counterBid', 'OfferController@bid_counter');

    Route::post('all-product','API\ProductController@allProduct');

    Route::post('follow',[\App\Http\Controllers\FollowController::class,'follow']);
    Route::post('un-follow',[\App\Http\Controllers\FollowController::class,'unFollow']);

    Route::post('ratting','API\UserController@ratting');
});

Route::get('/email/verify/{id}/{hash}', 'API\UserController@verify')->name('verification.verify');
Route::post('login', 'API\UserController@login')->name('users.login');
Route::post('privacy', 'API\UserController@privacy')->name('users.privacy');

Route::post('loginwithemail', 'API\UserController@loginwithemail')->name('users.loginwithemail');

Route::post('signup', 'API\UserController@store')->name('users.signup');
//Route::get('/email/resend', 'API\VerificationController@resend')->name('verification.resend');

Route::get('/email/verify/{id}/{hash}', 'API\UserController@verify')->name('verification.verify');

Route::post('forgot-password', 'API\UserController@forgot_password');

Route::get('password-reset-successful','API\UserController@passwordResetSuccessful')->name('password-reset-successful');
Route::get('cities', 'API\UserController@cities')->name('cities');
Route::get('countries', 'API\UserController@countries')->name('countries');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('change-password', 'API\AuthenticateController@change_password')->name('api.changepassword');
});
