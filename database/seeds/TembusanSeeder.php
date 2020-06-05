<?php

use App\Tembusan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TembusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Tembusan::create([
            'tembusan_isi' => 'Tembusan disampaikan kepada Yth :',
            'operator' => "Administrator",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
