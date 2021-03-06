<?php

namespace App\Http\Controllers;

use App\Tugas;
use App\Edaran;
use App\Review;
use App\Undangan;
use App\Disposisi;
use App\Pengantar;
use App\SuratMasuk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $auth = Auth::user();
        $edaran = Edaran::whereYear('edaran_tanggal', '=', date('Y'));
        $pengantar = Pengantar::whereYear('pengantar_tanggal', '=', date('Y'));
        $tugas = Tugas::whereYear('tugas_tanggal', '=', date('Y'));
        $undangan = Undangan::whereYear('undangan_tanggal', '=', date('Y'));

        if ($auth->getRoleNames()[0] != 'super-admin' && $auth->jabatan->jabatan_pimpinan == 0) {
            $edaran = $edaran->where('bidang_id', $auth->jabatan->bidang->bidang_id);
            $pengantar = $pengantar->where('bidang_id', $auth->jabatan->bidang->bidang_id);
            $tugas = $tugas->where('bidang_id', $auth->jabatan->bidang->bidang_id);
            $undangan = $undangan->where('bidang_id', $auth->jabatan->bidang->bidang_id);
        }

        $disposisi = [];
        if($auth->jabatan->jabatan_struktural == 1){
            if($auth->jabatan->jabatan_pimpinan == 1){
                $disposisi = SuratMasuk::where('disposisi', 0)->get([
                    'surat_masuk_id AS id',
                    'surat_masuk_nomor AS nomor',
                    'surat_masuk_asal AS asal',
                    'surat_masuk_perihal AS perihal',
                    DB::raw('"Surat Masuk" as jenis')
                ])->toArray();
            }else{
                $data = Disposisi::with('surat_masuk')->whereHas('detail', function ($q) use ($auth){
                    $q->where('jabatan_id', $auth->jabatan_id)->where('proses', 0);
                })->orderBy('created_at', 'desc')->get();
                foreach ($data as $row) {
                    array_push($disposisi, [
                        'id' => $row->disposisi_id,
                        'nomor' => $row->surat_masuk->surat_masuk_nomor,
                        'asal' => $row->surat_masuk->surat_masuk_asal,
                        'perihal' => $row->surat_masuk->surat_masuk_perihal,
                        'jenis' => "Surat Masuk"
                    ]);
                }
            }
        }
        return view('pages.dashboard.index', [
            'auth' => $auth,
            'disposisi' => collect($disposisi),
            'edaran' => [ $edaran->count(), $edaran->where('fix', 1)->count() ],
            'pengantar' => [ $pengantar->count(), $pengantar->where('fix', 1)->count() ],
            'tugas' => [ $tugas->count(), $tugas->where('fix', 1)->count() ],
            'undangan' => [ $undangan->count(), $undangan->where('fix', 1)->count() ],
            'review' => [
                Review::orderBy('created_at', 'asc')->where('operator', $auth->pengguna_id)->where('fix', 1)->where('selesai', 0)->get(),
                Review::orderBy('created_at', 'asc')->where('jabatan_id', $auth->jabatan_id)->whereNull('fix')->get()
                ]
        ]);
    }
}
