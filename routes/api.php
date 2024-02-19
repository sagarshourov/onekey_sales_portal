<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CallsController;

use App\Http\Controllers\NotiController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WaController;


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


Route::post('register', 'App\Http\Controllers\AuthController@store');
Route::post('login', 'App\Http\Controllers\AuthController@login');
Route::get('file/{folder}/{path}', 'App\Http\Controllers\FileController@getFile');
Route::post('forgot', 'App\Http\Controllers\AuthController@forgot');
Route::post('forgot_password', 'App\Http\Controllers\AuthController@forgot_password');

Route::get('calls_sorts', 'App\Http\Controllers\CallsController@calls_sorts');

Route::resource('whatsapp', WaController::class);
Route::post('record_upload', 'App\Http\Controllers\FileController@record_upload');
//Route::get('call_filter/{results}/{value}/{startDate}/{endDate}/{off}/{limit}/{search?}/{order}', 'App\Http\Controllers\CallsController@filter');
Route::group(["prefix" => "admin", 'middleware' => 'auth:api', "name" => "admin"], function () {
    Route::get('call_filter/{user_id}/{results}/{value}/{startDate?}/{endDate?}/{off}/{limit}/{search?}/{order}', 'App\Http\Controllers\CallsController@filter');

    Route::post('calls_sort', 'App\Http\Controllers\CallsController@calls_sort');
    Route::post('users_sort', 'App\Http\Controllers\UserController@users_sort');

    Route::post('assign_employee', 'App\Http\Controllers\UserController@assign_employee');

    Route::get('assign_employee/{id}', 'App\Http\Controllers\UserController@get_assign_employee');

    Route::post('del_assign_employee', 'App\Http\Controllers\UserController@del_assign_employee');

    Route::post('file_upload', 'App\Http\Controllers\FileController@file_upload');
    Route::get('check/{field}/{value}', 'App\Http\Controllers\CallsController@check');
    Route::get('userinfo/{id}', 'App\Http\Controllers\AuthController@userinfo');
    Route::resource('users', UserController::class);
    Route::resource('calls', CallsController::class);
    Route::put('call_single/{id}', 'App\Http\Controllers\CallsController@call_single');
    Route::post('call_export', 'App\Http\Controllers\CallsController@call_export');
    Route::resource('notifications', NotiController::class);

    Route::post('read_all_noti', 'App\Http\Controllers\NotiController@read_all_noti');


    Route::resource('settings', SettingsController::class);
    Route::get('settings/{table}/{id}', 'App\Http\Controllers\SettingsController@destroy');
    Route::get('events', 'App\Http\Controllers\CallsController@events');
    Route::get('activity/{id}', 'App\Http\Controllers\BaseController@logActivityLists');
    Route::get('token/{id}', 'App\Http\Controllers\AuthController@token');
    Route::get('whatsapp/record_history/{id}', 'App\Http\Controllers\FileController@record_history');
    Route::post('import', [CallsController::class, 'singleImport']);
    Route::post('call/import', [CallsController::class, 'import']);
    Route::post('call/import_file', [CallsController::class, 'import_file']);

    Route::get('reports/{emp_id}/{off}', 'App\Http\Controllers\CallsController@reports');

    Route::get('pre_filter/{startDate}/{endDate}/{users}/{type}/{off}/{limit}/{order}', 'App\Http\Controllers\CallsController@pre_filter');

    Route::get('emp_filter/{startDate}/{endDate}/{type}/{result}/{cancel}/{off}/{limit}/{order}', 'App\Http\Controllers\CallsController@emp_filter');

    Route::get('emp_fc_filter/{startDate}/{endDate}/{result}/{off}/{limit}/{order}', 'App\Http\Controllers\CallsController@emp_fc_filter');

    Route::get('emp_follow_filter/{startDate}/{endDate}/{result}/{off}/{limit}/{order}', 'App\Http\Controllers\CallsController@emp_follow_filter');

    Route::post('update_feedback', 'App\Http\Controllers\CallsController@update_feedback');

    Route::get('dashboard/{team}/{sdate}/{edate}', 'App\Http\Controllers\DashboardController@index');
});

Route::get('whatsapp/record_history/{id}', 'App\Http\Controllers\FileController@record_history');
Route::get('call/export', [UserController::class, 'export']);
Route::get('single_export/{result}/{title}/{user_id}', [UserController::class, 'single_export']);



Route::post('register_api', [CallsController::class, 'register_api']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('call_export', 'App\Http\Controllers\CallsController@call_export');
