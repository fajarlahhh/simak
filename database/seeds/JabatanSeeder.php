<?php

use App\Jabatan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Jabatan::insert([
            'jabatan_nama' => "Staff",
            'jabatan_parent' => null,
            'jabatan_silsilah' => null,
            'jabatan_pimpinan' => 0,
            'jabatan_struktural' => 0,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
