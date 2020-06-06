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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function(){
    Route::apiResource('users' , 'v1\usersController');
    Route::apiResource('certificates' , 'v1\certificatesController');
    Route::post('/users/login', 'v1\userLogIn');
    Route::get('/signup/activate/{token}', 'v1\usersController@signupActivate');
});
