<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' =>'v1'],function(){
    //main apis  //general apis
    Route::get('cities','Api\MainController@cities');
    Route::get('regions','Api\MainController@regions');
    Route::get('categories','Api\MainController@categories');
    Route::get('restaurants','Api\MainController@restaurants');
    Route::get('restaurant','Api\MainController@restaurant');
    Route::get('payment-types','Api\MainController@paymentTypes');
    Route::get('products','Api\MainController@products');
    Route::get('product','Api\MainController@product');
    Route::get('offers','Api\MainController@offers');
    Route::get('offer','Api\MainController@offer');
    Route::post('contact','Api\MainController@contact');
    Route::get('restaurant/reviews','Api\MainController@reviews');
    Route::get('settings','Api\MainController@settings');

    // client apis
    Route::group(['prefix' =>'client'],function(){
        // un authenticated client apis
        Route::post('register', 'Api\Client\AuthController@register');
        Route::post('login', 'Api\Client\AuthController@login');
        Route::post('reset-password', 'Api\Client\AuthController@resetPassword');
        Route::post('new-password', 'Api\Client\AuthController@newPassword');

        Route::group(['middleware'=>'auth:client'],function(){
            // authenticated client apis
            Route::post('profile', 'Api\Client\AuthController@profile');
            Route::post('register-token', 'Api\Client\AuthController@registerToken');
            Route::post('remove-token', 'Api\Client\AuthController@removeToken');
            Route::get('notifications','Api\Client\AuthController@notifications');

            // orders apis
            Route::post('new-order','Api\Client\MainController@newOrder');
            Route::get('orders','Api\Client\MainController@orders');
            Route::post('confirm-order','Api\Client\MainController@confirmOrder');
            Route::post('decline-order','Api\Client\MainController@declineOrder');

            Route::post('restaurant/review','Api\Client\MainController@review');
        });
    });

    // restaurant apis
    Route::group(['prefix' =>'restaurant'],function(){
        // un authenticated restaurant apis
        Route::post('register', 'Api\Restaurant\AuthController@register');
        Route::post('login', 'Api\Restaurant\AuthController@login');
        Route::post('reset-password', 'Api\Restaurant\AuthController@resetPassword');
        Route::post('new-password', 'Api\Restaurant\AuthController@newPassword');

        Route::group(['middleware'=>'auth:restaurant'],function(){
            // authenticated restaurant apis
            Route::post('profile', 'Api\Restaurant\AuthController@profile');
            Route::post('register-token', 'Api\Restaurant\AuthController@registerToken');
            Route::post('remove-token', 'Api\Restaurant\AuthController@removeToken');
            Route::get('notifications','Api\Restaurant\AuthController@notifications');

            // products apis
            Route::get('products','Api\Restaurant\MainController@products');
            Route::post('new-product','Api\Restaurant\MainController@newProduct');
            Route::post('update-product','Api\Restaurant\MainController@updateProduct');
            Route::post('delete-product','Api\Restaurant\MainController@deleteProduct');
            // offers apis
            Route::get('offers','Api\Restaurant\MainController@offers');
            Route::post('new-offer','Api\Restaurant\MainController@newOffer');
            Route::post('update-offer','Api\Restaurant\MainController@updateOffer');
            Route::post('delete-offer','Api\Restaurant\MainController@deleteOffer');
            // orders apis
            Route::get('orders','Api\Restaurant\MainController@orders');
            Route::post('accept-order','Api\Restaurant\MainController@acceptOrder');
            Route::post('reject-order','Api\Restaurant\MainController@rejectOrder');
           
            Route::post('status','Api\Restaurant\MainController@status');
            Route::get('commissions','Api\Restaurant\MainController@commissions');
        });
    });
});

