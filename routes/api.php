<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Controller\ServiceController;

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

Route::middleware('auth:api')->get('/user', 'UserController@getUserInfo');

Route::post('/login', 'UserController@login');
Route::post('/login_app', 'UserController@loginApp');
Route::post('/mail_verify', 'UserController@mailVerify');
Route::post('/users', 'UserController@storeApp');

Route::middleware('auth:api')->resource('/services', 'ServiceController');

Route::middleware('auth:api')->resource('/customers', 'CustomerController');
Route::put('/customers/profile/{id}', 'CustomerController@profileUpdate')->middleware('auth:api');
Route::get('/customer_by_user_id/{id}', 'CustomerController@customerByUserId')->middleware('auth:api');

Route::middleware('auth:api')->resource('/specialists', 'SpecialistController');
Route::get('/specialist_by_user_id/{id}', 'SpecialistController@specialistByUserId')->middleware('auth:api');
Route::put('/specialists/profile/{id}', 'SpecialistController@profileUpdate')->middleware('auth:api');

Route::middleware('auth:api')->resource('/addresses', 'AddressController');

Route::get('/image/{folder}/{id}', 'UploadController@getImage');
Route::post('/image/{folder}/{id}', 'UploadController@changeImage');
