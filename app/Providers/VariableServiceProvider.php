<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
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
            $foto = Auth::user() && Auth::user()->pengguna_foto? Storage::url(Auth::user()->pengguna_foto): '../assets/img/user/user.png';
            $nama = strtoupper(Redis::get(Session::getId()));
            return $view->with('foto_pegawai', $foto)->with('nama_pegawai', $nama);
        });
    }
}
