<?php

namespace App\Http\Controllers;

use App\Tugas;
use App\Edaran;
use App\Undangan;
use App\Pengantar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SuratkeluarController extends Controller
{
    //
	public function tracking(Request $req)
	{
        return view('pages.tracking.suratkeluar.index');
    }

	public function cari(Request $req)
	{
        $edaran = Edaran::with('bidang')->where(function($q) use ($req){
            $q->where('edaran_sifat', 'like', '%'.$req->cari.'%')->orWhere('edaran_perihal', 'like', '%'.$req->cari.'%')->orWhere('edaran_nomor', 'like', '%'.$req->cari.'%');
        })->get([
            'edaran_nomor AS nomor',
            'edaran_tanggal AS tanggal',
            'bidang_id AS bidang_id',
            'edaran_perihal AS perihal',
            'fix AS fix', 
            'operator AS operator', 
            DB::raw('"edaran" as jenis')
        ]);
        $pengantar = Pengantar::with('bidang')->where(function($q) use ($req){
            $q->where('pengantar_sifat', 'like', '%'.$req->cari.'%')->orWhere('pengantar_perihal', 'like', '%'.$req->cari.'%')->orWhere('pengantar_nomor', 'like', '%'.$req->cari.'%');
        })->get([
            'pengantar_nomor AS nomor',
            'pengantar_tanggal AS tanggal',
            'bidang_id AS bidang_id',
            'pengantar_perihal AS perihal',
            'fix AS fix', 
            'operator AS operator', 
            DB::raw('"pengantar" as jenis')
        ]);
        $tugas = Tugas::with('bidang')->where(function($q) use ($req){
            $q->where('tugas_sifat', 'like', '%'.$req->cari.'%')->orWhere('tugas_perihal', 'like', '%'.$req->cari.'%')->orWhere('tugas_nomor', 'like', '%'.$req->cari.'%');
        })->get([
            'tugas_nomor AS nomor',
            'tugas_tanggal AS tanggal',
            'bidang_id AS bidang_id',
            'tugas_perihal AS perihal',
            'fix AS fix', 
            'operator AS operator', 
            DB::raw('"tugas" as jenis')
        ]);
        $undangan = Undangan::with('bidang')->where(function($q) use ($req){
            $q->where('undangan_sifat', 'like', '%'.$req->cari.'%')->orWhere('undangan_perihal', 'like', '%'.$req->cari.'%')->orWhere('undangan_nomor', 'like', '%'.$req->cari.'%');
        })->get([
            'undangan_nomor AS nomor',
            'undangan_tanggal AS tanggal',
            'bidang_id AS bidang_id',
            'undangan_perihal AS perihal',
            'fix AS fix', 
            'operator AS operator', 
            DB::raw('"undangan" as jenis')
        ]);
        $surat_keluar = collect($edaran)->merge(collect($pengantar));
        $surat_keluar = $surat_keluar->merge(collect($tugas));
        $surat_keluar = $surat_keluar->merge(collect($undangan)); 
		return $surat_keluar;
    }
}
