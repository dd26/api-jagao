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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'UserController@login');
Route::post('/login_app', 'UserController@loginApp');
Route::post('/mail_verify', 'UserController@mailVerify');
Route::post('/users', 'UserController@storeApp');

Route::middleware('auth:api')->resource('/services', 'ServiceController');
Route::middleware('auth:api')->resource('/customers', 'CustomerController');
Route::middleware('auth:api')->resource('/specialists', 'SpecialistController');

Route::get('/image/{folder}/{id}', 'UploadController@getImage');
Route::post('/image/{folder}/{id}', 'UploadController@changeImage');
