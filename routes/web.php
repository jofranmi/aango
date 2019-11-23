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

Route::get('/', 'HomeController@index')->name('index');

Auth::routes();

Route::get('/home', 'HomeController@home')->name('home');

/*Route::get('/test', function () {
    $var = new \App\Models\ItemVehicle;
    dd($var->getTable());
});*/

Route::prefix('customers')->group(function () {
    Route::get('/', 'CustomerOfficeController@index');
});

Route::prefix('orders')->group(function () {
    Route::get('/', 'OrderController@index');
    Route::get('create', 'OrderController@createView');
    Route::post('filterOrders', 'OrderController@filterOrders');
});

Route::prefix('request')->group(function () {
    Route::post('getItemsFromVIN', 'RequestController@getItemsFromVIN');
    Route::post('createOrder', 'RequestController@createOrder');
    Route::post('createCustomer', 'RequestController@createCustomer');
    Route::post('editCustomer', 'RequestController@editCustomer');
    Route::post('getCustomerWithUsers', 'RequestController@getCustomerWithUsers');
    Route::post('removeUserFromCustomer', 'RequestController@removeUserFromCustomer');
    Route::post('addUserToCustomer', 'RequestController@addUserToCustomer');
});


