<?php

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

// Route::get('/home', 'HomeController@index');
Route::group(['middleware'=>[] , 'prefix' => 'admin'],function(){
    Route::get('/home','HomeController@index'); //
    Route::resource('city', 'CityController'); //
    Route::resource('region', 'RegionController'); //
    Route::resource('category', 'CategoryController'); //
    Route::resource('client','ClientController'); //
    Route::resource('offer', 'OfferController'); //
    Route::resource('order','OrderController');//
    Route::resource('contact','ContactController'); //
    Route::resource('payment-type','PaymentTypeController');

    Route::resource('restaurant','RestaurantController');
    Route::get('restaurant-activate/{id}', 'RestaurantController@activate')->name('restaurant.activate');
    Route::get('restaurant-deactivate/{id}', 'RestaurantController@deActivate')->name('restaurant.deactivate');
    
    Route::get('settings','SettingsController@view'); //
    Route::post('settings','SettingsController@update'); //


    // user reset
    Route::get('user/change-password','UserController@changePassword');
    Route::post('user/change-password','UserController@changePasswordSave');
//    Route::resource('user','UserController');
//    Route::resource('role','RoleController');

});    