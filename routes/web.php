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
    Route::put('/customer', 'CustomerController@store');
    Route::post('/customer/{id}', 'CustomerController@update');
    Route::get('/customers', 'CustomerController@index');
    Route::get('/customer/{id}', 'CustomerController@show');


    // change stock
    Route::post('/product/stock', 'ProductController@changeStockApi');


    // products
    Route::put('/product', 'ProductController@store');
    Route::get('/products', 'ProductController@index');
    Route::post('/product/{id}', 'ProductController@update');
    Route::get('/product/{id}', 'ProductController@show');

    // sales
    Route::get('/sales', 'SaleController@index');
    Route::post('/sale', 'SaleController@sale');
    Route::get('/sale/{id}', 'SaleController@show');


    // payment
    Route::put('/payment', 'PaymentController@store');
    Route::get('/payment/{id}', 'PaymentController@show');
    Route::post('/payment/{id}', 'PaymentController@update');
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
