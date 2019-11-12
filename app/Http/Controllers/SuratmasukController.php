<?php

namespace App\Http\Controllers;

use App\SuratMasuk;
use Illuminate\Http\Request;

class SuratmasukController extends Controller
{
    //
	public function index(Request $req)
	{
        $arsip = $req->arsip? $req->arsip: '0';
		$periode = $req->periode? $req->periode: date('F Y');
		$tanggal = $req->tanggal? $req->tanggal: 'surat_masuk_tanggal_terima';
		
		switch ($req->tipe) {
			case '0':
				$suratmasuk = SuratMasuk::with('pengarsipan')->whereRaw('date_format('.$tanggal.', \'%M %Y\')=\''.$periode.'\'')
								->where(function($q) use ($req){
									$q->where('surat_masuk_nomor', 'like', '%'.$req->cari.'%')
									->orWhere('surat_masuk_asal', 'like', '%'.$req->cari.'%')
									->orWhere('surat_masuk_penerima', 'like', '%'.$req->cari.'%')
									->orWhere('surat_masuk_perihal', 'like', '%'.$req->cari.'%');
								})
								->where(function($q) use ($arsip){
									if($arsip == '1'){
										$q->whereNotNull('penyimpanan_id');
									}else if($arsip == '2'){
										$q->whereNull('penyimpanan_id');
									}
									
								})
								->orderBy('surat_masuk_nomor')->paginate(10);
				break;
			case '1':
				$suratmasuk = SuratMasuk::with('pengarsipan')->onlyTrashed()
								->where(function($q) use ($req){
									$q->where('surat_masuk_nomor', 'like', '%'.$req->cari.'%')
									->orWhere('surat_masuk_asal', 'like', '%'.$req->cari.'%')
									->orWhere('surat_masuk_penerima', 'like', '%'.$req->cari.'%')
									->orWhere('surat_masuk_perihal', 'like', '%'.$req->cari.'%');
								})
								->where(function($q) use ($arsip){
									if($arsip == '1'){
										$q->whereNotNull('penyimpanan_id');
									}else if($arsip == '2'){
										$q->whereNull('penyimpanan_id');
									}
									
								})
								->orderBy('surat_masuk_nomor')->paginate(10);
				break;
			case '2':
				$suratmasuk = SuratMasuk::with('pengarsipan')->withTrashed()->whereRaw('date_format('.$tanggal.', \'%M %Y\')=\''.$periode.'\'')
								->where(function($q) use ($req){
									$q->where('surat_masuk_nomor', 'like', '%'.$req->cari.'%')
									->orWhere('surat_masuk_asal', 'like', '%'.$req->cari.'%')
									->orWhere('surat_masuk_penerima', 'like', '%'.$req->cari.'%')
									->orWhere('surat_masuk_perihal', 'like', '%'.$req->cari.'%');
								})
								->where(function($q) use ($arsip){
									if($arsip == '1'){
										$q->whereNotNull('penyimpanan_id');
									}else if($arsip == '2'){
										$q->whereNull('penyimpanan_id');
									}
									
								})
								->orderBy('surat_masuk_nomor')->paginate(10);
				break;

			default:
				$suratmasuk = SuratMasuk::with('pengarsipan')->whereRaw('date_format('.$tanggal.', \'%M %Y\')=\''.$periode.'\'')
								->where(function($q) use ($req){
									$q->where('surat_masuk_nomor', 'like', '%'.$req->cari.'%')
									->orWhere('surat_masuk_asal', 'like', '%'.$req->cari.'%')
									->orWhere('surat_masuk_penerima', 'like', '%'.$req->cari.'%')
									->orWhere('surat_masuk_perihal', 'like', '%'.$req->cari.'%');
								})
								->where(function($q) use ($arsip){
									if($arsip == '1'){
										$q->whereNotNull('penyimpanan_id');
									}else if($arsip == '2'){
										$q->whereNull('penyimpanan_id');
									}
									
								})
								->orderBy('surat_masuk_nomor')->paginate(10);
				break;
        }
		$suratmasuk->appends([
			'cari' => $req->cari, 
			'tipe' => $req->tipe, 
			'arsip' => $arsip, 
			'periode' => $periode]);
		return view('pages.datasurat.suratmasuk.index', [
            'data' => $suratmasuk,
            'i' => ($req->input('page', 1) - 1) * 10,
            'cari' => $req->cari,
            'tipe' => $req->tipe,
			'arsip' => $arsip,
			'tanggal' => $req->tanggal,
            'periode' => $periode,
        ]);
    }

	public function cari($cari)
	{
        return SuratMasuk::where('surat_masuk_nomor', 'like', '%'.$req->cari.'%')
        ->orWhere('surat_masuk_asal', 'like', '%'.$req->cari.'%')
        ->orderBy('surat_masuk_nomor')->get();
	}

	public function tambah()
	{
		return view('pages.datasurat.suratmasuk.form')
					->with('back', Str::contains(url()->previous(), ['suratmasuk/tambah', 'suratmasuk/edit'])? '/suratmasuk': url()->previous())
					->with('aksi', 'Tambah');
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'surat_masuk_nomor' => 'required',
				'surat_masuk_asal' => 'required'
			],[
         	   'surat_masuk_nomor.required' => 'Nama tempat permintaan barang tidak boleh kosong',
         	   'surat_masuk_asal.required'  => 'Deskripsi tidak boleh kosong',
        	]
		);
		try{
			$suratmasuk = new SuratMasuk();
			$suratmasuk->surat_masuk_nomor = $req->get('surat_masuk_nomor');
			$suratmasuk->pb_tanggal = $req->get('pb_tanggal');
			$suratmasuk->surat_masuk_penerima = $req->get('surat_masuk_penerima');
			$suratmasuk->surat_masuk_asal = $req->get('surat_masuk_asal');
            $suratmasuk->pb_file = $req->get('pb_file');
            if ($req->get('penyimpanan_id')) {
                $suratmasuk->penyimpanan_id = $req->get('penyimpanan_id');
                $suratmasuk->arsip_operator = ucfirst(strtolower(explode(', ', Redis::get(Session::getId()))[0]));
                $suratmasuk->arsip_waktu = Carbon::now();
            }
			$suratmasuk->created_operator = ucfirst(strtolower(explode(', ', Redis::get(Session::getId()))[0]));
            $suratmasuk->save();

			return redirect($req->get('redirect')? $req->get('redirect'): 'suratmasuk')
			->with('swal_pesan', 'Berhasil menambah data permintaan barang '.$req->get('surat_masuk_nomor'))
			->with('swal_judul', 'Tambah data')
			->with('swal_tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'suratmasuk')
			->with('swal_pesan', $e->getMessage())
			->with('swal_judul', 'Tambah data')
			->with('swal_tipe', 'error');
		}
	}

	public function edit($id)
	{
		try{
			$suratmasuk = SuratMasuk::findOrFail($id);
			return view('pages.datasurat.suratmasuk.form', compact('suratmasuk'))
						->with('suratmasuk', $suratmasuk)
						->with('back', Str::contains(url()->previous(), ['suratmasuk/tambah', 'suratmasuk/edit'])? '/suratmasuk': url()->previous())
						->with('aksi', 'Edit');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'suratmasuk')
			->with('swal_pesan', $e->getMessage())
			->with('swal_judul', 'Edit data')
			->with('swal_tipe', 'error');
		}
	}

	public function do_edit(Request $req)
	{
		$req->validate(
			[
				'surat_masuk_nomor' => 'required',
				'surat_masuk_asal' => 'required'
			],[
         	   'surat_masuk_nomor.required' => 'Nama permintaan barang tidak boleh kosong',
         	   'surat_masuk_asal.required'  => 'Deskripsi tidak boleh kosong',
        	]
		);
		try{
			$suratmasuk = SuratMasuk::findOrFail($req->get('penyimpanan_id'));
			$suratmasuk->surat_masuk_nomor = $req->get('surat_masuk_nomor');
			$suratmasuk->surat_masuk_asal = $req->get('surat_masuk_asal');
			$suratmasuk->operator = ucfirst(strtolower(explode(', ', Redis::get(Session::getId()))[0]));
			$suratmasuk->save();
			return redirect($req->get('redirect')? $req->get('redirect'): 'suratmasuk')
			->with('swal_pesan', 'Berhasil mengedit data permintaan barang '.$req->get('surat_masuk_nomor'))
			->with('swal_judul', 'Edit data')
			->with('swal_tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'suratmasuk')
			->with('swal_pesan', $e->getMessage())
			->with('swal_judul', 'Edit data')
			->with('swal_tipe', 'error');
		}
	}

	public function hapus($id)
	{
		try{
            $suratmasuk = SuratMasuk::findOrFail($id);
			$suratmasuk->delete();
			return response()->json([
				'swal_pesan' => 'Berhasil menghapus data permintaan barang '.$suratmasuk->surat_masuk_nomor,
				'swal_judul' => 'Hapus data',
				'swal_tipe' =>'success',
			]);
		}catch(\Exception $e){
			return response()->json([
				'swal_pesan' => $e->getMessage(),
				'swal_judul' => 'Hapus data',
				'swal_tipe' =>'error',
			]);
		}
	}

	public function hapus_permanen($id)
	{
		try{
            $suratmasuk = SuratMasuk::withTrashed()->findOrFail($id);
			$suratmasuk->forceDelete();
			return response()->json([
				'swal_pesan' => 'Berhasil menghapus secara permanen data permintaan barang '.$suratmasuk->surat_masuk_nomor,
				'swal_judul' => 'Hapus data',
				'swal_tipe' =>'success',
			]);
		}catch(\Exception $e){
			return response()->json([
				'swal_pesan' => $e->getMessage(),
				'swal_judul' => 'Hapus data',
				'swal_tipe' =>'error',
			]);
		}
	}

	public function restore($id)
	{
		try{
            $suratmasuk = SuratMasuk::findOrFail($id);
			$suratmasuk->restore();
			return response()->json([
				'swal_pesan' => 'Berhasil merestore data permintaan barang '.$suratmasuk->surat_masuk_nomor,
				'swal_judul' => 'Restore data',
				'swal_tipe' =>'success',
			]);
		}catch(\Exception $e){
			return response()->json([
				'swal_pesan' => $e->getMessage(),
				'swal_judul' => 'Restore data',
				'swal_tipe' =>'error',
			]);
		}
	}
}
