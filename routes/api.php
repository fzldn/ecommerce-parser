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

// Customer
Route::get('/customers', 'CustomerController@index');
Route::get('/customers/{customer}', 'CustomerController@show');

// Brand
Route::get('/brands', 'BrandController@index');
Route::get('/brands/{brand}', 'BrandController@show');

// Category
Route::get('/categories', 'CategoryController@index');
Route::get('/categories/{category}', 'CategoryController@show');

// Product
Route::get('/products', 'ProductController@index');
Route::get('/products/brands/{brand}', 'ProductController@indexByBrand');
Route::get('/products/categories/{category}', 'ProductController@indexByCategory');
Route::get('/products/{product}', 'ProductController@show');

// Order
Route::get('/orders', 'OrderController@index');
Route::get('/orders/{order}', 'OrderController@show');
