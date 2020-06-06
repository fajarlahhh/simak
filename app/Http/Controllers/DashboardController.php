<?php

namespace App\Http\Controllers;

use App\Tugas;
use App\Edaran;
use App\Review;
use App\Undangan;
use App\Pengantar;
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

        if ($auth->getRoleNames()[0] != 'super-admin') {
            $edaran = $edaran->where('bidang_id', $auth->jabatan->bidang->bidang_id);
            $pengantar = $pengantar->where('bidang_id', $auth->jabatan->bidang->bidang_id);
            $tugas = $tugas->where('bidang_id', $auth->jabatan->bidang->bidang_id);
            $undangan = $undangan->where('bidang_id', $auth->jabatan->bidang->bidang_id);
        }
        
        return view('pages.dashboard.index', [
            'auth' => $auth,
            'disposisi' => null,
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
