<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('/chat', 'ChatController@chat');

Route::prefix('chat')->group(function () {
    Route::post('send_msg', 'ChatController@sendMessage');
    Route::get('read_msg', 'ChatController@readMessage');
    Route::get('read_name', 'ChatController@readName');
    Route::get('read_status', 'ChatController@readStatus');
});


Route::get('/csrf_token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::prefix('mobile')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', 'Mobile\MobileAuthController@login');
        Route::post('token_login', 'Mobile\MobileAuthController@loginWithToken');
    });

});

Route::prefix('contact')->group(function () {
    Route::get('/', 'ContactController@getList')->middleware('auth.mobile');
    Route::post('del', 'ContactController@delete')->middleware('auth.mobile');
    Route::post('add', 'ContactController@add')->middleware('auth.mobile');
    Route::get('/test', 'ContactController@test');
    Route::post('/add_by_qr', 'ContactController@addByQRToken')->middleware('auth.mobile');
    Route::get('/create_add_qr', 'ContactController@createContactQRToken')->middleware('auth.mobile');
});

Route::prefix('profile')->group(function () {
    Route::get('/', 'ProfileController@getHomeProfile')->middleware('auth.mobile');
});

Route::resource('avatar', 'AvatarController');