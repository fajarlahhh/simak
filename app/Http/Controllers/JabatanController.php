<?php

namespace App\Http\Controllers;

use App\Jabatan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JabatanController extends Controller
{
    //
    public function __construct()
	{
		$this->middleware('auth');
    }

    public function index(Request $req)
	{
        $data = Jabatan::where(function($q) use ($req){
            $q->where('jabatan_nama', 'like', '%'.$req->cari.'%');
        })->orderBy('jabatan_nama')->paginate(10);

        $data->appends(['cari' => $req->cari]);
        return view('pages.setup.jabatan.index', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'cari' => $req->cari
        ]);
    }

	public function cari(Request $req)
	{
        $barang_dan_pekerjaan = Jabatan::where('jabatan_nama', 'like', '%'.$req->cari.'%')->orderBy('jabatan_nama')->get();
		return $barang_dan_pekerjaan;
    }

	public function tambah(Request $req)
	{
        try{
            return view('pages.datamaster.barangdanpekerjaan.form', [
                'aksi' => 'tambah',
                'back' => Str::contains(url()->previous(), ['barangdanpekerjaan/tambah', 'barangdanpekerjaan/edit'])? '/barangdanpekerjaan': url()->previous(),
            ]);
		}catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
			return redirect(url()->previous()? url()->previous(): 'barangdanpekerjaan');
		}
    }

	public function do_tambah(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'jabatan_nama' => 'required',
                'barang_dan_pekerjaan_harga' => 'required',
                'barang_dan_pekerjaan_satuan' => 'required',
                'barang_dan_pekerjaan_jenis' => 'required'
            ],[
                'jabatan_nama.required'  => 'Nama Barang/Pekerjaan tidak boleh kosong',
                'barang_dan_pekerjaan_harga.required'  => 'Harga Satuan (Rp.) tidak boleh kosong',
                'barang_dan_pekerjaan_satuan.required'  => 'Satuan tidak boleh kosong',
                'barang_dan_pekerjaan_jenis.required'  => 'Satuan tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            return implode('<br>', $validator->messages()->all());
        }

        try{
			$barang_dan_pekerjaan = new Jabatan();
			$barang_dan_pekerjaan->jabatan_nama = $req->get('jabatan_nama');
			$barang_dan_pekerjaan->barang_dan_pekerjaan_harga = str_replace(',', '', $req->get('barang_dan_pekerjaan_harga'));
			$barang_dan_pekerjaan->barang_dan_pekerjaan_satuan = $req->get('barang_dan_pekerjaan_satuan');
			$barang_dan_pekerjaan->barang_dan_pekerjaan_jenis = $req->get('barang_dan_pekerjaan_jenis');
			$barang_dan_pekerjaan->operator = Auth::id();
            $barang_dan_pekerjaan->save();
            toast('Berhasil menambah barang dan kegiatan '.$req->get('jabatan_nama'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('barangdanpekerjaan'));
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function edit(Request $req)
	{
        try{
            return view('pages.datamaster.barangdanpekerjaan.form', [
                'aksi' => 'edit',
                'data' => Jabatan::findOrFail($req->get('id')),
                'back' => Str::contains(url()->previous(), ['barangdanpekerjaan/tambah', 'barangdanpekerjaan/edit'])? '/barangdanpekerjaan': url()->previous(),
            ]);
		}catch(\Exception $e){
            alert()->error('Edit Data', $e->getMessage());
			return redirect(url()->previous()? url()->previous(): 'barangdanpekerjaan');
		}
    }

	public function do_edit(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'jabatan_nama' => 'required',
                'barang_dan_pekerjaan_harga' => 'required',
                'barang_dan_pekerjaan_satuan' => 'required',
                'barang_dan_pekerjaan_jenis' => 'required'
            ],[
                'jabatan_nama.required'  => 'Nama Barang/Pekerjaan tidak boleh kosong',
                'barang_dan_pekerjaan_harga.required'  => 'Harga Satuan (Rp.) tidak boleh kosong',
                'barang_dan_pekerjaan_satuan.required'  => 'Satuan tidak boleh kosong',
                'barang_dan_pekerjaan_jenis.required'  => 'Satuan tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            return implode('<br>', $validator->messages()->all());
        }

        try{
			$barang_dan_pekerjaan = Jabatan::findOrFail($req->get('id'));
			$barang_dan_pekerjaan->jabatan_nama = $req->get('jabatan_nama');
			$barang_dan_pekerjaan->barang_dan_pekerjaan_harga = str_replace(',', '', $req->get('barang_dan_pekerjaan_harga'));
			$barang_dan_pekerjaan->barang_dan_pekerjaan_satuan = $req->get('barang_dan_pekerjaan_satuan');
			$barang_dan_pekerjaan->barang_dan_pekerjaan_jenis = $req->get('barang_dan_pekerjaan_jenis');
			$barang_dan_pekerjaan->operator = Auth::id();
            $barang_dan_pekerjaan->save();
            toast('Berhasil menambah barang dan kegiatan '.$req->get('jabatan_nama'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('barangdanpekerjaan'));
        }catch(\Exception $e){
            alert()->error('Edit Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function hapus($id)
	{
		try{
            $barang_dan_pekerjaan = Jabatan::findOrFail($id);
            $barang_dan_pekerjaan->delete();
            toast('Berhasil menghapus barang dan pekerjaan '.$barang_dan_pekerjaan->jabatan_nama, 'success')->autoClose(2000);
		}catch(\Exception $e){
            alert()->error('Hapus Data', $e->getMessage());
		}
	}
}
