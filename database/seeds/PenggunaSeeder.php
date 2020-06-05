<?php

use App\Pengguna;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pengguna::insert([
            'pengguna_id' => "admin",
            'pengguna_nama' => "Administrator",
            'pengguna_sandi' => Hash::make('admin'),
            'remember_token' => Str::random(10),
            'token' => Hash::make('admin'),
            'jabatan_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
