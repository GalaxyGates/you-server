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

Route::post('/mobile/auth/login', 'Mobile\MobileAuthController@login');

Route::resource('avatar', 'AvatarController');