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
// verifyToken
Route::post('/verify_token', 'UserController@verifyToken');

Route::middleware('auth:api')->resource('/services', 'ServiceController');

Route::middleware('auth:api')->resource('/customers', 'CustomerController');
Route::put('/customers/profile/{id}', 'CustomerController@profileUpdate')->middleware('auth:api');
Route::get('/customer_by_user_id/{id}', 'CustomerController@customerByUserId')->middleware('auth:api');

Route::middleware('auth:api')->resource('/specialists', 'SpecialistController');
Route::get('/specialist_by_user_id/{id}', 'SpecialistController@specialistByUserId')->middleware('auth:api');
Route::put('/specialists/profile/{id}', 'SpecialistController@profileUpdate')->middleware('auth:api');

Route::middleware('auth:api')->resource('/addresses', 'AddressController');
Route::middleware('auth:api')->resource('/cards', 'CardController');
Route::middleware('auth:api')->resource('/banks', 'BankController');
Route::middleware('auth:api')->resource('/categories', 'CategoryController');

Route::middleware('auth:api')->resource('/subcategories', 'SubCategoryController');
Route::get('/subcategories_by_category_id/{id}', 'SubCategoryController@subcategoriesByCategoryId')->middleware('auth:api');

Route::post('/specialist_services', 'SpecialistServiceController@store')->middleware('auth:api');
Route::get('/specialist_services', 'SpecialistServiceController@index')->middleware('auth:api');
Route::get('/specialist_services/category/{category_id}', 'SpecialistServiceController@specialistServicesByCategory')->middleware('auth:api');

Route::put('/users/change_status', 'UserController@changeStatus')->middleware('auth:api');


Route::post('/master_request_services', 'MasterRequestServiceController@store')->middleware('auth:api');
Route::get('/master_request_services', 'MasterRequestServiceController@index')->middleware('auth:api');
Route::get('/master_request_services/{id}', 'MasterRequestServiceController@show')->middleware('auth:api');
Route::delete('/master_request_services/{id}', 'MasterRequestServiceController@destroy')->middleware('auth:api');
Route::get('/master_request_services/status/{status}', 'MasterRequestServiceController@indexByStatus')->middleware('auth:api');
Route::put('/master_request_services/{id}/status/{status}', 'MasterRequestServiceController@updateStatus')->middleware('auth:api');

Route::get('/notifications', 'NotificationController@index')->middleware('auth:api');

Route::get('/cities', 'CityController@index');
Route::get('/categories', 'CategoryController@index');

Route::get('/image/{folder}/{id}', 'UploadController@getImage');
Route::get('/image_two/{folder}/{name}', 'UploadController@getImageTwo');
Route::post('/image/{folder}/{id}', 'UploadController@changeImage');
