<?php

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
Route::delete('/x', function () {
    return 'DELETe OK ';
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Customers
Route::get('/customers','CustomerController@index');
Route::post('/customers','CustomerController@store');
Route::put('/customers/{id}','CustomerController@update');
Route::get('/customers/{id}','CustomerController@show');
Route::delete('/customers/{id}','CustomerController@destroy');
Route::get('/getCustomers','CustomerController@getCustomers');
Route::get('/searchCustomers','CustomerController@searchCustomers');
Route::get('/getCustomerInfo/{id}','CustomerController@getCustomerInfo');

//Products
Route::get('/products','ProductController@index')->name('products.index');
Route::post('/products','ProductController@store');
Route::post('/imgproducts/{id}','ProductController@updateImg');
Route::put('/products/{id}','ProductController@update');
Route::get('/products/{id}','ProductController@show');
Route::get('/getProducts','ProductController@getProducts');
Route::get('/searchProducts','ProductController@searchProducts');
Route::get('/getProductInfo/{id}','ProductController@getProductInfo');