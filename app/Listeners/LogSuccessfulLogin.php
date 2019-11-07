<?php

namespace App\Listeners;

use App\Pegawai;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $id = Auth::user()->pengguna_id;
        $pegawai = Pegawai::find($id);
        Redis::set(Session::getId(), $pegawai? $pegawai->nm_pegawai: $id);
    }
}
