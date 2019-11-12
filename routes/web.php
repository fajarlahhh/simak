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

    Route::group(['middleware' => ['role_or_permission:admin|datapengguna']], function () {
		Route::get('/datapengguna', 'PenggunaController@index')->name('datapengguna');
		Route::get('/datapengguna/edit/{id}', 'PenggunaController@edit')->middleware(['role:admin|user']);
		Route::put('/datapengguna/edit', 'PenggunaController@do_edit')->middleware(['role:admin|user'])->name('datapengguna.edit');
		Route::get('/datapengguna/tambah', 'PenggunaController@tambah')->middleware(['role:admin|user'])->name('datapengguna.tambah');
		Route::post('/datapengguna/tambah', 'PenggunaController@do_tambah')->middleware(['role:admin|user']);
		Route::delete('/datapengguna/hapus/{id}', 'PenggunaController@hapus')->middleware(['role:admin|user']);
	});

    Route::group(['middleware' => ['role_or_permission:admin|datapenyimpanan']], function () {
        Route::prefix('datapenyimpanan')->group(function () {
            Route::get('/', 'PenyimpananController@index')->name('datapenyimpanan');
            Route::get('edit/{id}', 'PenyimpananController@edit')->middleware(['role:admin|user']);
            Route::put('edit', 'PenyimpananController@do_edit')->middleware(['role:admin|user'])->name('datapenyimpanan.edit');
            Route::get('tambah', 'PenyimpananController@tambah')->middleware(['role:admin|user'])->name('datapenyimpanan.tambah');
            Route::post('tambah', 'PenyimpananController@do_tambah')->middleware(['role:admin|user']);
            Route::patch('hapus/{id}', 'PenyimpananController@hapus')->middleware(['role:admin|user']);
            Route::patch('restore/{id}', 'PenyimpananController@hapus')->middleware(['role:admin|user']);
            Route::delete('hapuspermanen/{id}', 'PenyimpananController@hapus_permanen')->middleware(['role:admin|user']);
        });
	});

    Route::group(['middleware' => ['role_or_permission:admin|suratmasuk']], function () {
        Route::prefix('suratmasuk')->group(function () {
            Route::get('/', 'SuratmasukController@index')->name('suratmasuk');
            Route::get('tambah', 'SuratmasukController@tambah')->middleware(['role:admin|user'])->name('suratmasuk.tambah');
            Route::post('tambah', 'SuratmasukController@do_tambah')->middleware(['role:admin|user']);
            Route::get('edit/{id}', 'SuratmasukController@edit')->middleware(['role:admin|user']);
            Route::put('edit', 'SuratmasukController@do_edit')->middleware(['role:admin|user'])->name('suratmasuk.edit');
            Route::get('pengarsipan/{id}', 'SuratmasukController@pengarsipan')->middleware(['role:admin|user']);
            Route::put('pengarsipan', 'SuratmasukController@do_pengarsipan')->middleware(['role:admin|user']);
            Route::patch('hapus/{id}', 'SuratmasukController@hapus')->middleware(['role:admin|user']);
            Route::patch('restore/{id}', 'SuratmasukController@hapus')->middleware(['role:admin|user']);
            Route::delete('hapuspermanen/{id}', 'SuratmasukController@hapus_permanen')->middleware(['role:admin|user']);
        });
	});
});

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');

Route::get('/logout', 'Auth\LoginController@logout');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
