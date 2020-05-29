<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KopSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('kop_surat')->insert([
            'kop_isi' => '<table border="0" cellpadding="1" cellspacing="1" style="width:100%">
            <tbody>
                <tr>
                    <td style="text-align:justify"><img alt="" src="/uploads/file/1590708757SlqcF986q2LQckJI.png" style="float:right; height:148px; width:100px" /></td>
                    <td style="text-align:center">
                    <p><span style="font-size:14px"><strong><span style="font-family:Times New Roman,Times,serif">PEMERINTAH PROVINSI NUSA TENGGARA BARAT</span></strong></span></p>

                    <p><span style="font-size:14px"><strong><span style="font-family:Times New Roman,Times,serif">DINAS PENDIDIKAN DAN KEBUDAYAAN</span></strong></span></p>

                    <p><span style="font-family:Times New Roman,Times,serif">Jalan Pendidikan No. 19 A Mataram - Telp (0370) 632593 - Fax. (0370) 632593</span></p>

                    <p><span style="font-family:Times New Roman,Times,serif">Situs Resmi : <a href="http://dikbud.ntbprov.go.id">http://dikbud.ntbprov.go.id</a>&nbsp;Email : dikbud@ntbprov.go.id</span></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <hr />',
            'operator' => "Administrator",
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
