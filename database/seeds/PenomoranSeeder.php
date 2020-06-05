<?php

use App\Penomoran;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PenomoranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        //
        $jenis = array('edaran','pengantar','sk','tugas','undangan');

        $nomor = array('420/$urut$.$bidang$/Dikbud/$tahun$', '045/$urut$.$bidang$/Dikbud/$tahun$', '188.4/$urut$.$bidang$/Dikbud/$tahun$', '090/$urut$.$bidang$/Dikbud/$tahun$', '005/$urut$.$bidang$/Dikbud/$tahun$');
        $i = 0;
        foreach ($jenis as $jenis) {
            Penomoran::create([
                'penomoran_jenis' => $jenis,
                'penomoran_format' => $nomor[$i],
                'operator' => "Administrator",
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]);
            $i++;
        }
    }
}
