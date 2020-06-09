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
    Route::get('/dataopd/cari', 'OpdController@cari')->middleware(['role:super-admin|user|supervisor']);

    Route::group(['middleware' => ['role_or_permission:super-admin|datapengguna']], function () {
        Route::prefix('datapengguna')->group(function () {
            Route::get('/', 'PenggunaController@index')->name('datapengguna');
            Route::get('/edit/{id}', 'PenggunaController@edit')->middleware(['role:super-admin|user|supervisor']);
            Route::put('/edit', 'PenggunaController@do_edit')->middleware(['role:super-admin|user|supervisor'])->name('datapengguna.edit');
            Route::get('/tambah', 'PenggunaController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('datapengguna.tambah');
            Route::post('/tambah', 'PenggunaController@do_tambah')->middleware(['role:super-admin|user|supervisor'])->name('datapengguna.tambah');
            Route::delete('/hapus/{id}', 'PenggunaController@hapus')->middleware(['role:super-admin|user|supervisor']);
            Route::patch('/restore/{id}', 'PenggunaController@restore')->middleware(['role:super-admin|supervisor']);
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
            Route::get('/edit/{id}', 'BidangController@edit')->middleware(['role:super-admin|user|supervisor']);
            Route::put('/edit', 'BidangController@do_edit')->middleware(['role:super-admin|user|supervisor'])->name('databidang.edit');
            Route::get('/tambah', 'BidangController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('databidang.tambah');
            Route::get('/tambah', 'BidangController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('databidang.tambah');
            Route::post('/tambah', 'BidangController@do_tambah')->middleware(['role:super-admin|user|supervisor'])->name('databidang.tambah');
            Route::delete('/hapus/{id}', 'BidangController@hapus')->middleware(['role:super-admin|user|supervisor']);
            Route::patch('/restore', 'BidangController@restore')->middleware(['role:super-admin|supervisor']);
            Route::get('/detail', 'BidangController@detail')->name('databidang.detail');
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|dataopd']], function () {
        Route::prefix('dataopd')->group(function () {
            Route::get('/', 'OpdController@index')->name('dataopd');
            Route::get('/edit', 'OpdController@edit')->middleware(['role:super-admin|user|supervisor']);
            Route::put('/edit', 'OpdController@do_edit')->middleware(['role:super-admin|user|supervisor'])->name('dataopd.edit');
            Route::get('/tambah', 'OpdController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('dataopd.tambah');
            Route::get('/tambah', 'OpdController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('dataopd.tambah');
            Route::post('/tambah', 'OpdController@do_tambah')->middleware(['role:super-admin|user|supervisor'])->name('dataopd.tambah');
            Route::delete('/hapus', 'OpdController@hapus')->middleware(['role:super-admin|user|supervisor']);
            Route::patch('/restore', 'OpdController@restore')->middleware(['role:super-admin|supervisor']);
            Route::get('/detail', 'OpdController@detail')->name('dataopd.detail');
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|suratmasuk']], function () {
        Route::prefix('suratmasuk')->group(function () {
            Route::get('/', 'SuratmasukController@index')->name('suratmasuk');
            Route::get('/edit/{id}', 'SuratmasukController@edit')->middleware(['role:super-admin|user|supervisor']);
            Route::put('/edit', 'SuratmasukController@do_edit')->middleware(['role:super-admin|user|supervisor'])->name('suratmasuk.edit');
            Route::get('/tambah', 'SuratmasukController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('suratmasuk.tambah');
            Route::post('/tambah', 'SuratmasukController@do_tambah')->middleware(['role:super-admin|user|supervisor'])->name('suratmasuk.tambah');
            Route::delete('/hapus', 'SuratmasukController@hapus')->middleware(['role:super-admin|user|supervisor']);
            Route::patch('/restore', 'SuratmasukController@restore')->middleware(['role:super-admin|supervisor']);
            Route::get('/detail', 'SuratmasukController@detail')->name('suratmasuk.detail');
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|trackingsuratmasuk']], function () {
        Route::prefix('trackingsuratmasuk')->group(function () {
            Route::get('/', 'SuratmasukController@tracking')->name('suratmasuk');
            Route::get('/cari', 'SuratmasukController@cari')->middleware(['role:super-admin|user|supervisor']);
            Route::get('/detail/{id}', 'SuratmasukController@do_tracking')->middleware(['role:super-admin|user|supervisor']);
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|trackingsuratkeluar']], function () {
        Route::prefix('trackingsuratkeluar')->group(function () {
            Route::get('/', 'SuratkeluarController@tracking')->name('suratmasuk');
            Route::get('/cari', 'SuratkeluarController@cari')->middleware(['role:super-admin|user|supervisor']);
            Route::get('/detail/edaran', 'EdaranController@detail')->middleware(['role:super-admin|user|supervisor']);
            Route::get('/detail/pengantar', 'PengantarController@detail')->middleware(['role:super-admin|user|supervisor']);
            Route::get('/detail/tugas', 'TugasController@detail')->middleware(['role:super-admin|user|supervisor']);
            Route::get('/detail/undangan', 'UndanganController@detail')->middleware(['role:super-admin|user|supervisor']);
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|edaran']], function () {
        Route::prefix('edaran')->group(function () {
            Route::get('/', 'EdaranController@index')->name('edaran');
            Route::get('/edit', 'EdaranController@edit')->middleware(['role:super-admin|user|supervisor']);
            Route::get('/edit/isi', 'EdaranController@edit_isi')->middleware(['role:super-admin|user|supervisor']);
            Route::put('/edit', 'EdaranController@do_edit')->middleware(['role:super-admin|user|supervisor'])->name('edaran.edit');
            Route::get('/tambah', 'EdaranController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('edaran.tambah');
            Route::post('/tambah', 'EdaranController@do_tambah')->middleware(['role:super-admin|user'])->name('edaran.tambah');
            Route::delete('/hapus', 'EdaranController@hapus')->middleware(['role:super-admin|user|supervisor']);
            Route::delete('/hapus/lampiran', 'EdaranController@hapus_lampiran')->middleware(['role:super-admin|user|supervisor']);
            Route::patch('/restore', 'EdaranController@restore')->middleware(['role:super-admin|supervisor']);
            Route::get('/detail', 'EdaranController@detail')->name('edaran.detail');
            Route::get('/cetak', 'EdaranController@cetak')->middleware(['role:super-admin|user'])->name('edaran.cetak');
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|undangan']], function () {
        Route::prefix('undangan')->group(function () {
            Route::get('/', 'UndanganController@index')->name('undangan');
            Route::get('/edit', 'UndanganController@edit')->middleware(['role:super-admin|user|supervisor']);
            Route::get('/edit/isi', 'UndanganController@edit_isi')->middleware(['role:super-admin|user|supervisor']);
            Route::put('/edit', 'UndanganController@do_edit')->middleware(['role:super-admin|user|supervisor'])->name('undangan.edit');
            Route::get('/tambah', 'UndanganController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('undangan.tambah');
            Route::post('/tambah', 'UndanganController@do_tambah')->middleware(['role:super-admin|user'])->name('undangan.tambah');
            Route::delete('/hapus', 'UndanganController@hapus')->middleware(['role:super-admin|user|supervisor']);
            Route::delete('/hapus/lampiran', 'UndanganController@hapus_lampiran')->middleware(['role:super-admin|user|supervisor']);
            Route::patch('/restore', 'UndanganController@restore')->middleware(['role:super-admin|supervisor']);
            Route::get('/detail', 'UndanganController@detail')->name('undangan.detail');
            Route::get('/cetak', 'UndanganController@cetak')->middleware(['role:super-admin|user'])->name('undangan.cetak');
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|pengantar']], function () {
        Route::prefix('pengantar')->group(function () {
            Route::get('/', 'PengantarController@index')->name('pengantar');
            Route::get('/edit', 'PengantarController@edit')->middleware(['role:super-admin|user|supervisor']);
            Route::get('/edit/isi', 'PengantarController@edit_isi')->middleware(['role:super-admin|user|supervisor']);
            Route::put('/edit', 'PengantarController@do_edit')->middleware(['role:super-admin|user|supervisor'])->name('pengantar.edit');
            Route::get('/tambah', 'PengantarController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('pengantar.tambah');
            Route::post('/tambah', 'PengantarController@do_tambah')->middleware(['role:super-admin|user'])->name('pengantar.tambah');
            Route::delete('/hapus', 'PengantarController@hapus')->middleware(['role:super-admin|user|supervisor']);
            Route::delete('/hapus/lampiran', 'PengantarController@hapus_lampiran')->middleware(['role:super-admin|user|supervisor']);
            Route::patch('/restore', 'PengantarController@restore')->middleware(['role:super-admin|supervisor']);
            Route::get('/detail', 'PengantarController@detail')->name('pengantar.detail');
            Route::get('/cetak', 'PengantarController@cetak')->middleware(['role:super-admin|user'])->name('pengantar.cetak');
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|tugas']], function () {
        Route::prefix('tugas')->group(function () {
            Route::get('/', 'TugasController@index')->name('tugas');
            Route::get('/edit', 'TugasController@edit')->middleware(['role:super-admin|user|supervisor']);
            Route::get('/edit/isi', 'TugasController@edit_isi')->middleware(['role:super-admin|user|supervisor']);
            Route::put('/edit', 'TugasController@do_edit')->middleware(['role:super-admin|user|supervisor'])->name('tugas.edit');
            Route::get('/tambah', 'TugasController@tambah')->middleware(['role:super-admin|user|supervisor'])->name('tugas.tambah');
            Route::post('/tambah', 'TugasController@do_tambah')->middleware(['role:super-admin|user'])->name('tugas.tambah');
            Route::delete('/hapus', 'TugasController@hapus')->middleware(['role:super-admin|user|supervisor']);
            Route::delete('/hapus/lampiran', 'TugasController@hapus_lampiran')->middleware(['role:super-admin|user|supervisor']);
            Route::patch('/restore', 'TugasController@restore')->middleware(['role:super-admin|supervisor']);
            Route::get('/detail', 'TugasController@detail')->name('tugas.detail');
            Route::get('/cetak', 'TugasController@cetak')->middleware(['role:super-admin|user'])->name('tugas.cetak');
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|review']], function () {
        Route::prefix('review')->group(function () {
            Route::get('/', 'ReviewController@index');
            Route::get('/cek', 'ReviewController@review')->middleware(['role:super-admin|user|supervisor'])->name('review');
            Route::put('/cek', 'ReviewController@do_review')->middleware(['role:super-admin|user|supervisor'])->name('review.simpan');
            Route::put('/selesai', 'ReviewController@selesai')->middleware(['role:super-admin|user|supervisor'])->name('review.selesai');
        });
    });

    Route::group(['middleware' => ['role_or_permission:super-admin|disposisi']], function () {
        Route::prefix('disposisi')->group(function () {
            Route::get('/', 'DisposisiController@index');
            Route::get('/form', 'DisposisiController@disposisi')->middleware(['role:super-admin|user|supervisor'])->name('disposisi');
            Route::put('/form', 'DisposisiController@do_disposisi')->middleware(['role:super-admin|user|supervisor'])->name('disposisi.simpan');
            Route::put('/selesai', 'DisposisiController@selesai')->middleware(['role:super-admin|user|supervisor'])->name('disposisi.selesai');
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

    Route::group(['middleware' => ['role_or_permission:super-admin|tembusan']], function () {
        Route::prefix('tembusan')->group(function () {
            Route::get('/', 'TembusanController@index')->name('tembusan');
            Route::post('/simpan', 'TembusanController@simpan')->middleware(['role:super-admin|user|supervisor'])->name('tembusan.simpan');
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
