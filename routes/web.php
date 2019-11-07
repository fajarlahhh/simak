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

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/gantisandi', 'PenggunaController@ganti_sandi')->name('gantisandi');

    Route::group(['middleware' => ['role_or_permission:admin|datapengguna']], function () {
		Route::get('/datapengguna', 'PenggunaController@index')->name('datapengguna');
		Route::get('/datapengguna/edit/{id}', 'PenggunaController@edit')->middleware(['role:admin|user']);
		Route::put('/datapengguna/edit', 'PenggunaController@do_edit')->middleware(['role:admin|user'])->name('datapengguna.edit');
		Route::get('/datapengguna/tambah', 'PenggunaController@tambah')->middleware(['role:admin|user'])->name('datapengguna.tambah');
		Route::post('/datapengguna/tambah', 'PenggunaController@do_tambah')->middleware(['role:admin|user']);
		Route::delete('/datapengguna/hapus/{id}', 'PenggunaController@hapus')->middleware(['role:admin|user']);
	});

    Route::group(['middleware' => ['role_or_permission:admin|datapenyimpanan']], function () {
		Route::get('/datapenyimpanan', 'PenyimpananController@index')->name('datapenyimpanan');
		Route::get('/datapenyimpanan/edit/{id}', 'PenyimpananController@edit')->middleware(['role:admin|user']);
		Route::put('/datapenyimpanan/edit', 'PenyimpananController@do_edit')->middleware(['role:admin|user'])->name('datapenyimpanan.edit');
		Route::get('/datapenyimpanan/tambah', 'PenyimpananController@tambah')->middleware(['role:admin|user'])->name('datapenyimpanan.tambah');
		Route::post('/datapenyimpanan/tambah', 'PenyimpananController@do_tambah')->middleware(['role:admin|user']);
		Route::patch('/datapenyimpanan/hapus/{id}', 'PenyimpananController@hapus')->middleware(['role:admin|user']);
		Route::patch('/datapenyimpanan/restore/{id}', 'PenyimpananController@hapus')->middleware(['role:admin|user']);
		Route::delete('/datapenyimpanan/hapuspermanen/{id}', 'PenyimpananController@hapus_permanen')->middleware(['role:admin|user']);
	});
});

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');

Route::get('/logout', 'Auth\LoginController@logout');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
