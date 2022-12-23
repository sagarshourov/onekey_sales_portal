<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CallsController;
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

Route::get('userinfo/{id}', 'App\Http\Controllers\AuthController@userinfo');





Route::group(["prefix" => "admin", 'middleware' => 'auth:api', "name" => "admin"], function () {
    Route::resource('users', UserController::class);
    Route::resource('calls', CallsController::class);
});




// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
