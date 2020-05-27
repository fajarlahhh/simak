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
    Route::get('/', 'DashboardController@index');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/gantisandi', 'PenggunaController@ganti_sandi')->name('gantisandi');
    Route::patch('/gantisandi', 'PenggunaController@do_ganti_sandi')->name('gantisandi');

    Route::group(['middleware' => ['role_or_permission:super-admin|datapengguna']], function () {
        Route::prefix('datapengguna')->group(function () {
            Route::get('/', 'PenggunaController@index')->name('datapengguna');
            Route::get('/edit/{id}', 'PenggunaController@edit')->middleware(['role:super-admin|user']);
            Route::put('/edit', 'PenggunaController@do_edit')->middleware(['role:super-admin|user'])->name('datapengguna.edit');
            Route::get('/tambah', 'PenggunaController@tambah')->middleware(['role:super-admin|user'])->name('datapengguna.tambah');
            Route::post('/tambah', 'PenggunaController@do_tambah')->middleware(['role:super-admin|user'])->name('datapengguna.tambah');
            Route::delete('/hapus/{id}', 'PenggunaController@hapus')->middleware(['role:super-admin|user']);
            Route::patch('/restore', 'PenggunaController@restore')->middleware(['role:super-admin|supervisor']);
            Route::get('/detail', 'PenggunaController@detail')->name('datapengguna.detail');
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|datajabatan']], function () {
        Route::prefix('datajabatan')->group(function () {
            Route::get('/', 'JabatanController@index')->name('datajabatan');
            Route::get('/edit/{id}', 'JabatanController@edit')->middleware(['role:super-admin|user']);
            Route::put('/edit', 'JabatanController@do_edit')->middleware(['role:super-admin|user'])->name('datajabatan.edit');
            Route::get('/tambah', 'JabatanController@tambah')->middleware(['role:super-admin|user'])->name('datajabatan.tambah');
            Route::post('/tambah', 'JabatanController@do_tambah')->middleware(['role:super-admin|user'])->name('datajabatan.tambah');
            Route::delete('/hapus/{id}', 'JabatanController@hapus')->middleware(['role:super-admin|user']);
            Route::patch('/restore', 'JabatanController@restore')->middleware(['role:super-admin|supervisor']);
            Route::get('/detail', 'JabatanController@detail')->name('datajabatan.detail');
        });
    });
});

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');

Route::get('/logout', 'Auth\LoginController@logout');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
