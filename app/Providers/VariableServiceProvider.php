<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class VariableServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view){
            $foto = '../assets/img/user/user.png';
            $nama = Auth::check()? strtoupper(Auth::user()->pengguna_nama): null;
            return $view->with('foto_pegawai', $foto)->with('nama_pegawai', $nama);
        });
    }
}
