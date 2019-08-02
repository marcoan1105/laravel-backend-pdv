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



// user
Route::put('/user', 'UserController@store');
Route::get('/user/login', 'UserController@login')->name('login');


Route::group(['middleware' => 'auth:api'], function(){

    // customer
    Route::post('/customer', 'CustomerController@store');
    Route::put('/customer/{id}', 'CustomerController@update');
    Route::get('/customers', 'CustomerController@index');
    Route::get('/customer/{id}', 'CustomerController@show');


    // change stock
    Route::post('/product/stock', 'ProductController@changeStockApi');


    // products
    Route::post('/product', 'ProductController@store');
    Route::get('/products', 'ProductController@index');
    Route::put('/product/{id}', 'ProductController@update');
    Route::get('/product/{id}', 'ProductController@show');

    // sales
    Route::get('/sales', 'SaleController@index');
    Route::post('/sale', 'SaleController@sale');
    Route::get('/sale/{id}', 'SaleController@show');


    // payment
    Route::post('/payment', 'PaymentController@store');
    Route::get('/payment/{id}', 'PaymentController@show');
    Route::put('/payment/{id}', 'PaymentController@update');
    Route::get('/payments', 'PaymentController@index');

    // user
    Route::get('/user', 'UserController@user');
    Route::put('/user/{id}', 'UserController@update');
});

Route::any('{all}', function (){
    return [
        "status" => false,
        "errors" => [
            "route" => "Not found"
        ]
    ];
});
