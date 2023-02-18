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

Route::resource('whatsapp', WaController::class);
Route::post('record_upload', 'App\Http\Controllers\FileController@record_upload');

Route::group(["prefix" => "admin", 'middleware' => 'auth:api', "name" => "admin"], function () {
    Route::get('call_filter/{results}/{value}', 'App\Http\Controllers\CallsController@filter');
    Route::post('file_upload', 'App\Http\Controllers\FileController@file_upload');
    Route::get('check/{field}/{value}', 'App\Http\Controllers\CallsController@check');
    Route::get('userinfo/{id}', 'App\Http\Controllers\AuthController@userinfo');
    Route::resource('users', UserController::class);
    Route::resource('calls', CallsController::class);
    Route::put('call_single/{id}', 'App\Http\Controllers\CallsController@call_single');
    Route::post('call_export', 'App\Http\Controllers\CallsController@call_export');
    Route::resource('notifications', NotiController::class);
    Route::resource('settings', SettingsController::class);
    Route::get('settings/{table}/{id}', 'App\Http\Controllers\SettingsController@destroy');
    Route::get('events', 'App\Http\Controllers\CallsController@events');
    Route::get('activity/{id}', 'App\Http\Controllers\BaseController@logActivityLists');
    Route::get('token/{id}', 'App\Http\Controllers\AuthController@token');
    Route::get('whatsapp/record_history/{id}', 'App\Http\Controllers\FileController@record_history');
    Route::post('call/import', [CallsController::class, 'import']);
    Route::post('call/import_file', [CallsController::class, 'import_file']);

   
});

Route::get('whatsapp/record_history/{id}', 'App\Http\Controllers\FileController@record_history');
Route::get('call/export/', [UserController::class, 'export']);



Route::post('register_api', [CallsController::class, 'register_api']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
