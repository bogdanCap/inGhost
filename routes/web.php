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

Route::get('/home', 'HomeController@index')->name('home');

//chat routes
Route::middleware(['auth', 'userActivity'])->group(function () {
    Route::get('/chat', 'ChatsController@index');
    Route::get('/messages', 'ChatsController@fetchMessages');
    Route::get('/activeUsers', 'ChatsController@getOnlineUsers');
    Route::post('/messages', 'ChatsController@sendMessage');
    Route::post('/chat/auth', 'ChatsController@pusherAuth');
});


