<?php

namespace App\Http\Controllers;

use App\Penyimpanan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenyimpananController extends Controller
{
    //
	public function index(Request $req)
	{
		switch ($req->tipe) {
			case '0':
				$penyimpanan = Penyimpanan::where('penyimpanan_nama', 'like', '%'.$req->cari.'%')
								->orWhere('penyimpanan_deskripsi', 'like', '%'.$req->cari.'%')
								->orderBy('penyimpanan_nama')->paginate(10);
				break;
			case '1':
				$penyimpanan = Penyimpanan::onlyTrashed()->orderBy('penyimpanan_nama')->paginate(10);
				break;
			case '2':
				$penyimpanan = Penyimpanan::withTrashed()->where('penyimpanan_nama', 'like', '%'.$req->cari.'%')
								->orWhere('penyimpanan_deskripsi', 'like', '%'.$req->cari.'%')
								->orderBy('penyimpanan_nama')->paginate(10);
				break;

			default:
				$penyimpanan = Penyimpanan::where('penyimpanan_nama', 'like', '%'.$req->cari.'%')
								->orWhere('penyimpanan_deskripsi', 'like', '%'.$req->cari.'%')
								->orderBy('penyimpanan_nama')->paginate(10);
				break;
		}
		$penyimpanan->appends([$req->cari, $req->tipe]);
		return view('pages.setup.penyimpanan.index', compact('penyimpanan'))
					->with('i', ($req->input('page', 1) - 1) * 5)
					->with('cari', $req->cari)
					->with('tipe', $req->tipe);
    }

	public function cari($cari)
	{
        return Penyimpanan::where('penyimpanan_nama', 'like', '%'.$req->cari.'%')
        ->orWhere('penyimpanan_deskripsi', 'like', '%'.$req->cari.'%')
        ->orderBy('penyimpanan_nama')->get();
	}

	public function tambah()
	{
		return view('pages.setup.penyimpanan.form')
					->with('back', Str::contains(url()->previous(), ['datapenyimpanan/tambah', 'datapenyimpanan/edit'])? '/datapenyimpanan': url()->previous())
					->with('aksi', 'Tambah');
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'penyimpanan_nama' => 'required',
				'penyimpanan_deskripsi' => 'required'
			],[
         	   'penyimpanan_nama.required' => 'Nama tempat penyimpanan tidak boleh kosong',
         	   'penyimpanan_deskripsi.required'  => 'Deskripsi tidak boleh kosong',
        	]
		);
		try{
			$penyimpanan = new Penyimpanan();
			$penyimpanan->penyimpanan_nama = $req->get('penyimpanan_nama');
			$penyimpanan->penyimpanan_deskripsi = $req->get('penyimpanan_deskripsi');
			$penyimpanan->operator = Auth::user()->pegawai->nm_pegawai;
            $penyimpanan->save();

			return redirect($req->get('redirect')? $req->get('redirect'): 'datapenyimpanan')
			->with('swal_pesan', 'Berhasil menambah data penyimpanan '.$req->get('penyimpanan_nama'))
			->with('swal_judul', 'Tambah data')
			->with('swal_tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'penyimpanan')
			->with('swal_pesan', $e->getMessage())
			->with('swal_judul', 'Tambah data')
			->with('swal_tipe', 'error');
		}
	}

	public function edit($id)
	{
		try{
			$penyimpanan = Penyimpanan::findOrFail($id);
			return view('pages.setup.penyimpanan.form', compact('penyimpanan'))
						->with('penyimpanan', $penyimpanan)
						->with('back', Str::contains(url()->previous(), ['datapenyimpanan/tambah', 'datapenyimpanan/edit'])? '/datapenyimpanan': url()->previous())
						->with('aksi', 'Edit');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapenyimpanan')
			->with('swal_pesan', $e->getMessage())
			->with('swal_judul', 'Edit data')
			->with('swal_tipe', 'error');
		}
	}

	public function do_edit(Request $req)
	{
		$req->validate(
			[
				'penyimpanan_nama' => 'required',
				'penyimpanan_deskripsi' => 'required'
			],[
         	   'penyimpanan_nama.required' => 'Nama penyimpanan tidak boleh kosong',
         	   'penyimpanan_deskripsi.required'  => 'Deskripsi tidak boleh kosong',
        	]
		);
		try{
			$penyimpanan = Penyimpanan::findOrFail($req->get('penyimpanan_id'));
			$penyimpanan->penyimpanan_nama = $req->get('penyimpanan_nama');
			$penyimpanan->penyimpanan_deskripsi = $req->get('penyimpanan_deskripsi');
			$penyimpanan->operator = Auth::user()->pegawai->nm_pegawai;
			$penyimpanan->save();
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapenyimpanan')
			->with('swal_pesan', 'Berhasil mengedit data penyimpanan '.$req->get('penyimpanan_nama'))
			->with('swal_judul', 'Edit data')
			->with('swal_tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapenyimpanan')
			->with('swal_pesan', $e->getMessage())
			->with('swal_judul', 'Edit data')
			->with('swal_tipe', 'error');
		}
	}

	public function hapus($id)
	{
		try{
            $penyimpanan = Penyimpanan::findOrFail($id);
			$penyimpanan->delete();
			return response()->json([
				'swal_pesan' => 'Berhasil menghapus data penyimpanan '.$penyimpanan->penyimpanan_nama,
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
            $penyimpanan = Penyimpanan::withTrashed()->findOrFail($id);
			$penyimpanan->forceDelete();
			return response()->json([
				'swal_pesan' => 'Berhasil menghapus secara permanen data penyimpanan '.$penyimpanan->penyimpanan_nama,
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
            $penyimpanan = Penyimpanan::findOrFail($id);
			$penyimpanan->restore();
			return response()->json([
				'swal_pesan' => 'Berhasil merestore data penyimpanan '.$penyimpanan->penyimpanan_nama,
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
