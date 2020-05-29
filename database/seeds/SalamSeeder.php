<?php

use App\Salam;
use Illuminate\Database\Seeder;

class SalamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Salam::insert([
            'salam_pembuka' => "<p><em><strong>Bismillaahirrahmaanirrahim, </strong></em></p>

            <p><em><strong>Assalamu&rsquo;alaikum warahmatullahi wabarakaatuh</strong></em></p>
            ",
            'salam_penutup' => "<p><strong><em>Wassalamu&rsquo;alaikum &nbsp;warahmatullahi wabarakatuh.</em></strong></p>
            ",
            'operator' => 'Administrator',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
