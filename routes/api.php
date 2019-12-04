<?php

use Illuminate\Http\Request;

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


Route::post('Login', 'UserController@login');

Route::post('Register', 'UserController@store');

Route::middleware(['Checkout'])->group(function(){

    Route::apiResource('Category', 'CategoryController');
    Route::get('Show_Category', 'CategoryController@show_categories');

    Route::apiResource('Password', 'PasswordController');
    Route::get('Show_Password', 'PasswordController@show_passwords');

    Route::apiResource('User', 'UserController');

});