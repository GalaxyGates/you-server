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
Route::get('/csrf_token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});
Route::prefix('auth')->group(function () {
        Route::post('login', 'API\AuthController@login');
        Route::post('token_login', 'API\AuthController@loginWithToken');
    });
Route::prefix('contact')->group(function () {
    Route::get('/', 'API\ContactController@getList')->middleware('auth.mobile');
    Route::post('del', 'API\ContactController@delete')->middleware('auth.mobile');
    Route::post('add', 'API\ContactController@add')->middleware('auth.mobile');
    Route::get('/test', 'API\ContactController@test');
    Route::post('/add_by_qr', 'API\ContactController@addByQRToken')->middleware('auth.mobile');
    Route::get('/create_add_qr', 'API\ContactController@createContactQRToken')->middleware('auth.mobile');
});

Route::prefix('profile')->group(function () {
    Route::get('/', 'API\ProfileController@getHomeProfile')->middleware('auth.mobile');
    Route::post('/','API\ProfileController@updateProfile')->middleware('auth.mobile');
});

Route::resource('avatar', 'API\AvatarController');