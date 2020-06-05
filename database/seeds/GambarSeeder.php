<?php

use App\Gambar;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GambarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Gambar::insert([
            'gambar_nama' => "Logo NTB",
            'gambar_lokasi' => "/uploads/gambar/logo_ntb.png",
            'operator' => "Administrator",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
