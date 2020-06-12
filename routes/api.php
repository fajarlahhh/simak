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

$router->get('/', function (){
    return "API SIMAK DINAS PENDIDIKAN PROVINSI NTB";
});

Route::post('/login/{api}', 'Auth\LoginController@login');

Route::prefix('review')->group(function () {
    Route::get('/{pengguna}', 'ReviewController@get');
    Route::post('/form', 'ReviewController@review');
    Route::put('/review', 'ReviewController@do_review');
});

Route::prefix('disposisi')->group(function () {
    Route::get('/{pengguna}', 'DisposisiController@get');
    Route::post('/form', 'DisposisiController@disposisi');
    Route::put('/disposisi', 'DisposisiController@do_disposisi');
    Route::put('/selesai', 'DisposisiController@selesai');
});
