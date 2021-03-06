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
    /*
    if (!$user = \Illuminate\Support\Facades\Auth::user()) {
        if (Illuminate\Support\Facades\Auth::attempt([
            'email' => 'bog@ram.com', 'password' => 'password'
        ]))
        {

        }
    };
    */

    return $request->user();
});

Route::get('/test', function (Request $request) {
    dd('ok');
});
