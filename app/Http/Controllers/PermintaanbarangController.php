<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\PermintaanBarang;
use Illuminate\Http\Request;

class PermintaanbarangController extends Controller
{
    //
	public function index(Request $req)
	{
        $penyimpanan = $req->penyimpanan? $req->penyimpanan: '%%';
		switch ($req->tipe) {
			case '0':
				$permintaanbarang = PermintaanBarang::where('pb_nomor', 'like', '%'.$req->cari.'%')
								->orWhere('pb_keterangan', 'like', '%'.$req->cari.'%')
								->orWhere('pb_peminta', 'like', '%'.$req->cari.'%')
								->orderBy('pb_nomor')->paginate(10);
				break;
			case '1':
				$permintaanbarang = PermintaanBarang::onlyTrashed()->orderBy('pb_nomor')->paginate(10);
				break;
			case '2':
				$permintaanbarang = PermintaanBarang::withTrashed()->where('pb_nomor', 'like', '%'.$req->cari.'%')
								->orWhere('pb_keterangan', 'like', '%'.$req->cari.'%')
								->orWhere('pb_peminta', 'like', '%'.$req->cari.'%')
								->orderBy('pb_nomor')->paginate(10);
				break;

			default:
				$permintaanbarang = PermintaanBarang::where('pb_nomor', 'like', '%'.$req->cari.'%')
								->orWhere('pb_keterangan', 'like', '%'.$req->cari.'%')
								->orWhere('pb_peminta', 'like', '%'.$req->cari.'%')
								->orderBy('pb_nomor')->paginate(10);
				break;
		}
		$permintaanbarang->appends([$req->cari, $req->tipe]);
		return view('pages.datasurat.permintaanbarang.index', [
            'data' => $permintaanbarang,
            'i' => (($req->input('page', 1) - 1) * 5),
            'cari' => $req->cari,
            'tipe' => $req->tipe,
        ]);
    }

	public function cari($cari)
	{
        return PermintaanBarang::where('pb_nomor', 'like', '%'.$req->cari.'%')
        ->orWhere('pb_keterangan', 'like', '%'.$req->cari.'%')
        ->orderBy('pb_nomor')->get();
	}

	public function tambah()
	{
		return view('pages.datasurat.permintaanbarang.form')
					->with('back', Str::contains(url()->previous(), ['permintaanbarang/tambah', 'permintaanbarang/edit'])? '/permintaanbarang': url()->previous())
					->with('aksi', 'Tambah');
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'pb_nomor' => 'required',
				'pb_keterangan' => 'required'
			],[
         	   'pb_nomor.required' => 'Nama tempat permintaan barang tidak boleh kosong',
         	   'pb_keterangan.required'  => 'Deskripsi tidak boleh kosong',
        	]
		);
		try{
			$permintaanbarang = new PermintaanBarang();
			$permintaanbarang->pb_nomor = $req->get('pb_nomor');
			$permintaanbarang->pb_tanggal = $req->get('pb_tanggal');
			$permintaanbarang->pb_peminta = $req->get('pb_peminta');
			$permintaanbarang->pb_keterangan = $req->get('pb_keterangan');
            $permintaanbarang->pb_file = $req->get('pb_file');
            if ($req->get('penyimpanan_id')) {
                $permintaanbarang->penyimpanan_id = $req->get('penyimpanan_id');
                $permintaanbarang->arsip_operator = ucfirst(strtolower(explode(', ', Redis::get(Session::getId()))[0]));
                $permintaanbarang->arsip_waktu = Carbon::now();
            }
			$permintaanbarang->created_operator = ucfirst(strtolower(explode(', ', Redis::get(Session::getId()))[0]));
            $permintaanbarang->save();

			return redirect($req->get('redirect')? $req->get('redirect'): 'permintaanbarang')
			->with('swal_pesan', 'Berhasil menambah data permintaan barang '.$req->get('pb_nomor'))
			->with('swal_judul', 'Tambah data')
			->with('swal_tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'permintaanbarang')
			->with('swal_pesan', $e->getMessage())
			->with('swal_judul', 'Tambah data')
			->with('swal_tipe', 'error');
		}
	}

	public function edit($id)
	{
		try{
			$permintaanbarang = PermintaanBarang::findOrFail($id);
			return view('pages.datasurat.permintaanbarang.form', compact('permintaanbarang'))
						->with('permintaanbarang', $permintaanbarang)
						->with('back', Str::contains(url()->previous(), ['permintaanbarang/tambah', 'permintaanbarang/edit'])? '/permintaanbarang': url()->previous())
						->with('aksi', 'Edit');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'permintaanbarang')
			->with('swal_pesan', $e->getMessage())
			->with('swal_judul', 'Edit data')
			->with('swal_tipe', 'error');
		}
	}

	public function do_edit(Request $req)
	{
		$req->validate(
			[
				'pb_nomor' => 'required',
				'pb_keterangan' => 'required'
			],[
         	   'pb_nomor.required' => 'Nama permintaan barang tidak boleh kosong',
         	   'pb_keterangan.required'  => 'Deskripsi tidak boleh kosong',
        	]
		);
		try{
			$permintaanbarang = PermintaanBarang::findOrFail($req->get('penyimpanan_id'));
			$permintaanbarang->pb_nomor = $req->get('pb_nomor');
			$permintaanbarang->pb_keterangan = $req->get('pb_keterangan');
			$permintaanbarang->operator = ucfirst(strtolower(explode(', ', Redis::get(Session::getId()))[0]));
			$permintaanbarang->save();
			return redirect($req->get('redirect')? $req->get('redirect'): 'permintaanbarang')
			->with('swal_pesan', 'Berhasil mengedit data permintaan barang '.$req->get('pb_nomor'))
			->with('swal_judul', 'Edit data')
			->with('swal_tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'permintaanbarang')
			->with('swal_pesan', $e->getMessage())
			->with('swal_judul', 'Edit data')
			->with('swal_tipe', 'error');
		}
	}

	public function hapus($id)
	{
		try{
            $permintaanbarang = PermintaanBarang::findOrFail($id);
			$permintaanbarang->delete();
			return response()->json([
				'swal_pesan' => 'Berhasil menghapus data permintaan barang '.$permintaanbarang->pb_nomor,
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
            $permintaanbarang = PermintaanBarang::withTrashed()->findOrFail($id);
			$permintaanbarang->forceDelete();
			return response()->json([
				'swal_pesan' => 'Berhasil menghapus secara permanen data permintaan barang '.$permintaanbarang->pb_nomor,
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
            $permintaanbarang = PermintaanBarang::findOrFail($id);
			$permintaanbarang->restore();
			return response()->json([
				'swal_pesan' => 'Berhasil merestore data permintaan barang '.$permintaanbarang->pb_nomor,
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
