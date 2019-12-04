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

Route::get('/', 'HomeController@home')->name('index')->middleware('auth');

Route::get('/home', 'HomeController@home')->name('home')->middleware('auth');

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);

Route::prefix('customers')->middleware('auth', 'office')->group(function () {
    Route::get('/', 'CustomerController@index');
});

Route::prefix('users')->middleware('auth', 'office')->group(function () {
    Route::get('/', 'UserController@index');
});

Route::prefix('items')->middleware('auth', 'office')->group(function () {
	Route::get('/', 'ItemController@index');
});

Route::prefix('orders')->middleware('auth')->group(function () {
    Route::get('/', 'OrderController@index');
    Route::get('create', 'OrderController@createView');
    Route::post('filterOrders', 'OrderController@filterOrders');
});

Route::prefix('request')->middleware('auth')->group(function () {
    Route::post('getItemsFromVIN', 'RequestController@getItemsFromVIN');
    Route::post('createOrder', 'RequestController@createOrder');
    Route::post('getOrderDetailsFromVIN', 'RequestController@getOrderDetailsFromVIN');
    Route::middleware(['office'])->group(function () {
        Route::post('createCustomer', 'RequestController@createCustomer');
        Route::post('editCustomer', 'RequestController@editCustomer');
        Route::post('getCustomerWithUsers', 'RequestController@getCustomerWithUsers');
        Route::post('removeUserFromCustomer', 'RequestController@removeUserFromCustomer');
        Route::post('addUserToCustomer', 'RequestController@addUserToCustomer');
        Route::post('getUser', 'RequestController@getUser');
        Route::post('createUser', 'RequestController@createUser');
        Route::post('editUser', 'RequestController@editUser');
        Route::post('updateOrderStatus', 'RequestController@updateOrderStatus');
        Route::post('createItemType', 'RequestController@createItemType');
        Route::post('getKeysForMake', 'RequestController@getKeysForMake');
        Route::post('getItemVehicle', 'RequestController@getItemVehicle');
        Route::post('editItemVehicle', 'RequestController@editItemVehicle');
        Route::post('getModelsFromMake', 'RequestController@getModelsFromMake');
        Route::post('getVehicleFromVIN', 'RequestController@getVehicleFromVIN');
        Route::post('createOrderComment', 'RequestController@createOrderComment');
        Route::post('deleteUser', 'RequestController@deleteUser');
    });
});


