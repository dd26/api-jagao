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

// eliminar el usuario logueado
Route::put('/users/logged/status/deleted', 'UserController@changeStatusInDelete')->middleware('auth:api');

Route::post('/verify_token', 'UserController@verifyToken');

Route::middleware('auth:api')->resource('/services', 'ServiceController');
Route::get('services/image/{id}', 'ServiceController@getImage');

Route::middleware('auth:api')->resource('/customers', 'CustomerController');
Route::put('/customers/profile/{id}', 'CustomerController@profileUpdate')->middleware('auth:api');
Route::get('/customer_by_user_id/{id}', 'CustomerController@customerByUserId')->middleware('auth:api');

Route::middleware('auth:api')->resource('/specialists', 'SpecialistController');
Route::get('/specialist_by_user_id/{id}', 'SpecialistController@specialistByUserId')->middleware('auth:api');
Route::put('/specialists/profile/{id}', 'SpecialistController@profileUpdate')->middleware('auth:api');
Route::get('/specialists/amount/total', 'SpecialistController@getAmountFinish')->middleware('auth:api');
Route::post('/download_cv/{id}', 'SpecialistController@downloadCv');

Route::middleware('auth:api')->resource('/addresses', 'AddressController');
Route::put('/addresses/{id}/status/{status}', 'AddressController@disableOrEnable')->middleware('auth:api');
Route::get('/addresses/status/{status}', 'AddressController@getAddressesByStatus')->middleware('auth:api');
Route::middleware('auth:api')->resource('/cards', 'CardController');
Route::middleware('auth:api')->resource('/banks', 'BankController');
Route::middleware('auth:api')->resource('/categories', 'CategoryController');
Route::put('/categories/{id}/status_change/', 'CategoryController@disableOrEnable')->middleware('auth:api');
Route::get('categories/specialist/not_worked', 'CategoryController@getCategoriesNotWorked')->middleware('auth:api');
Route::get('categories_actives', 'CategoryController@getCategoriesActives');

Route::middleware('auth:api')->resource('/coupons', 'CouponController');
Route::put('/coupons/{id}/status/{status}', 'CouponController@updateStatus')->middleware('auth:api');
Route::get('/coupons/check/code/{code}', 'CouponController@checkCouponByCode')->middleware('auth:api');

Route::middleware('auth:api')->resource('/subcategories', 'SubCategoryController');
Route::get('/subcategories_by_category_id/{id}', 'SubCategoryController@subcategoriesByCategoryId')->middleware('auth:api');

// Route::post('/specialist_services', 'SpecialistServiceController@store')->middleware('auth:api');
Route::get('/specialist_services', 'SpecialistServiceController@index')->middleware('auth:api');
Route::delete('/specialist_services/category/{category_id}', 'SpecialistServiceController@destroy')->middleware('auth:api');
Route::get('/specialist_services/category/{category_id}', 'SpecialistServiceController@specialistServicesByCategory')->middleware('auth:api');
Route::post('/specialist_services/category/{category_id}', 'SpecialistServiceController@store')->middleware('auth:api');

Route::put('/users/change_status', 'UserController@changeStatus')->middleware('auth:api');
Route::put('/users/verified/user/{id}', 'UserController@verifiedUser')->middleware('auth:api');

Route::post('/master_request_services', 'MasterRequestServiceController@store')->middleware('auth:api');
Route::get('/master_request_services', 'MasterRequestServiceController@index')->middleware('auth:api');
Route::get('/master_request_services/{id}', 'MasterRequestServiceController@show')->middleware('auth:api');
Route::delete('/master_request_services/{id}', 'MasterRequestServiceController@destroy')->middleware('auth:api');
Route::get('/master_request_services/status/{status}', 'MasterRequestServiceController@indexByStatus')->middleware('auth:api');
Route::put('/master_request_services/{id}/status/{status}', 'MasterRequestServiceController@updateStatus')->middleware('auth:api');
Route::get('/master_request_services/status/{status}/customer', 'MasterRequestServiceController@indexByStatusAndCustomer')->middleware('auth:api');
Route::get('/master_request_services/status/{status}/specialist', 'MasterRequestServiceController@indexByStatusAndSpecialist')->middleware('auth:api');
Route::put('/master_request_services/{id}/date/change', 'MasterRequestServiceController@updateDateRequest')->middleware('auth:api');

Route::get('/notifications', 'NotificationController@index')->middleware('auth:api');

// calification
Route::post('/califications/{master_request_service_id}', 'CalificationController@store')->middleware('auth:api');
Route::get('/califications/{master_request_service_id}', 'CalificationController@show')->middleware('auth:api');


// CRUD users_admin
Route::get('/users_admin', 'UserController@indexAdmin')->middleware('auth:api');
Route::post('/users_admin', 'UserController@storeAdmin')->middleware('auth:api');
Route::delete('/users_admin/{id}', 'UserController@destroyAdmin')->middleware('auth:api');
Route::get('/users_admin/{id}', 'UserController@showAdmin')->middleware('auth:api');
Route::put('/users_admin/{id}', 'UserController@updateAdmin')->middleware('auth:api');
Route::put('/users_admin/{id}/status', 'UserController@updateStatusUserAdm')->middleware('auth:api');


Route::get('/cities', 'CityController@index');
Route::get('/categories', 'CategoryController@index');

Route::get('/image/{folder}/{id}', 'UploadController@getImage');
Route::get('/image_two/{folder}/{name}', 'UploadController@getImageTwo');
Route::post('/image/{folder}/{id}', 'UploadController@changeImage');

Route::post('test_stripe', 'PaymentController@pruebasPagosStripe');

// prueba send mail
Route::get('/send_mail', 'MailController@sendMail');
Route::post('/recuperate_pass', 'MailController@sendMailRecuperatePassword');
Route::post('/recuperate_pass_app', 'MailController@sendMailRecuperatePasswordApp');
Route::post('/change_password', 'MailController@changePassword');
Route::get('/verify_code/{code}', 'MailController@verifyCode');
