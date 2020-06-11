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

Route::prefix('review')->group(function () {
    Route::get('/', 'ReviewController@index');
    Route::get('/form', 'ReviewController@review');
    Route::put('/review', 'ReviewController@do_review');
    Route::put('/selesai', 'ReviewController@selesai');
});

Route::prefix('disposisi')->group(function () {
    Route::get('/', 'DisposisiController@index');
    Route::get('/form', 'DisposisiController@disposisi');
    Route::put('/disposisi', 'DisposisiController@do_disposisi');
    Route::put('/selesai', 'DisposisiController@selesai');
});
