<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pengguna')->insert([
            'pengguna_id' => "admin",
            'pengguna_nama' => "Administrator",
            'pengguna_sandi' => Hash::make('admin'),
            'remember_token' => Str::random(10),
            'token' => Hash::make('admin'),
            'jabatan_nama' => "Staff",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
