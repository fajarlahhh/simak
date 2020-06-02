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
            Route::get('/edit/{id}', 'PenggunaController@edit')->middleware(['role:super-admin|user|supervisor']);
            Route::put('/edit', 'PenggunaController@do_edit')->middleware(['role:super-admin|user|supervisor'])->name('datapengguna.edit');
            Route::get('/tambah', 'PenggunaController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('datapengguna.tambah');
            Route::post('/tambah', 'PenggunaController@do_tambah')->middleware(['role:super-admin|user|supervisor'])->name('datapengguna.tambah');
            Route::delete('/hapus/{id}', 'PenggunaController@hapus')->middleware(['role:super-admin|user|supervisor']);
            Route::patch('/restore', 'PenggunaController@restore')->middleware(['role:super-admin|supervisor']);
            Route::get('/detail', 'PenggunaController@detail')->name('datapengguna.detail');
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|datajabatan']], function () {
        Route::prefix('datajabatan')->group(function () {
            Route::get('/', 'JabatanController@index')->name('datajabatan');
            Route::get('/edit/{id}', 'JabatanController@edit')->middleware(['role:super-admin|user|supervisor']);
            Route::put('/edit', 'JabatanController@do_edit')->middleware(['role:super-admin|user|supervisor'])->name('datajabatan.edit');
            Route::get('/tambah', 'JabatanController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('datajabatan.tambah');
            Route::post('/tambah', 'JabatanController@do_tambah')->middleware(['role:super-admin|user|supervisor'])->name('datajabatan.tambah');
            Route::delete('/hapus/{id}', 'JabatanController@hapus')->middleware(['role:super-admin|user|supervisor']);
            Route::patch('/restore', 'JabatanController@restore')->middleware(['role:super-admin|supervisor']);
            Route::get('/detail', 'JabatanController@detail')->name('datajabatan.detail');
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|databidang']], function () {
        Route::prefix('databidang')->group(function () {
            Route::get('/', 'BidangController@index')->name('databidang');
            Route::get('/edit', 'BidangController@edit')->middleware(['role:super-admin|user|supervisor']);
            Route::put('/edit', 'BidangController@do_edit')->middleware(['role:super-admin|user|supervisor'])->name('databidang.edit');
            Route::get('/tambah', 'BidangController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('databidang.tambah');
            Route::get('/tambah', 'BidangController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('databidang.tambah');
            Route::post('/tambah', 'BidangController@do_tambah')->middleware(['role:super-admin|user|supervisor'])->name('databidang.tambah');
            Route::delete('/hapus', 'BidangController@hapus')->middleware(['role:super-admin|user|supervisor']);
            Route::patch('/restore', 'BidangController@restore')->middleware(['role:super-admin|supervisor']);
            Route::get('/detail', 'BidangController@detail')->name('databidang.detail');
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|suratmasuk']], function () {
        Route::prefix('suratmasuk')->group(function () {
            Route::get('/', 'SuratmasukController@index')->name('suratmasuk');
            Route::get('/edit', 'SuratmasukController@edit')->middleware(['role:super-admin|user|supervisor']);
            Route::put('/edit', 'SuratmasukController@do_edit')->middleware(['role:super-admin|user|supervisor'])->name('suratmasuk.edit');
            Route::get('/tambah', 'SuratmasukController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('suratmasuk.tambah');
            Route::post('/tambah', 'SuratmasukController@do_tambah')->middleware(['role:super-admin|user|supervisor'])->name('suratmasuk.tambah');
            Route::delete('/hapus', 'SuratmasukController@hapus')->middleware(['role:super-admin|user|supervisor']);
            Route::patch('/restore', 'SuratmasukController@restore')->middleware(['role:super-admin|supervisor']);
            Route::get('/detail', 'SuratmasukController@detail')->name('suratmasuk.detail');
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|edaran']], function () {
        Route::prefix('edaran')->group(function () {
            Route::get('/', 'EdaranController@index')->name('edaran');
            Route::get('/edit', 'EdaranController@edit')->middleware(['role:super-admin|user|supervisor']);
            Route::put('/edit', 'EdaranController@do_edit')->middleware(['role:super-admin|user|supervisor'])->name('edaran.edit');
            Route::get('/tambah', 'EdaranController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('edaran.tambah');
            Route::post('/tambah', 'EdaranController@do_tambah')->middleware(['role:super-admin|user'])->name('edaran.tambah');
            Route::delete('/hapus', 'EdaranController@hapus')->middleware(['role:super-admin|user|supervisor']);
            Route::patch('/restore', 'EdaranController@restore')->middleware(['role:super-admin|supervisor']);
            Route::get('/detail', 'EdaranController@detail')->name('edaran.detail');
            Route::get('/cetak', 'EdaranController@cetak')->middleware(['role:super-admin|user'])->name('edaran.cetak');
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|gambar']], function () {
        Route::prefix('gambar')->group(function () {
            Route::get('/', 'GambarController@index')->name('gambar');
            Route::get('/tambah', 'GambarController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('gambar.tambah');
            Route::post('/tambah', 'GambarController@do_tambah')->middleware(['role:super-admin|user|supervisor'])->name('gambar.tambah');
            Route::delete('/hapus', 'GambarController@hapus')->middleware(['role:super-admin|user|supervisor']);
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|kopsurat']], function () {
        Route::prefix('kopsurat')->group(function () {
            Route::get('/', 'KopsuratController@index')->name('kopsurat');
            Route::post('/simpan', 'KopsuratController@simpan')->middleware(['role:super-admin|user|supervisor'])->name('kopsurat.simpan');
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|salam']], function () {
        Route::prefix('salam')->group(function () {
            Route::get('/', 'SalamController@index')->name('salam');
            Route::post('/simpan', 'SalamController@simpan')->middleware(['role:super-admin|user|supervisor'])->name('salam.simpan');
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|penomoran']], function () {
        Route::prefix('penomoran')->group(function () {
            Route::get('/', 'PenomoranController@index')->name('penomoran');
            Route::post('/simpan', 'PenomoranController@simpan')->middleware(['role:super-admin|user|supervisor'])->name('penomoran.simpan');
        });
    });
});

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');

Route::get('/logout', 'Auth\LoginController@logout');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::prefix('cetak')->group(function () {
    Route::get('/edaran', 'EdaranController@cetak');
});