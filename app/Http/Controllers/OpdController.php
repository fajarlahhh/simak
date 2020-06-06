<?php

namespace App\Http\Controllers;

use App\Opd;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OpdController extends Controller
{

    public function index(Request $req)
	{
        $data = Opd::where(function($q) use ($req){
            $q->where('opd_nama', 'like', '%'.$req->cari.'%');
        })->orderBy('opd_nama')->paginate(10);

        $data->appends(['cari' => $req->cari]);
        return view('pages.datamaster.opd.index', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'cari' => $req->cari
        ]);
    }

	public function tambah(Request $req)
	{
        return view('pages.datamaster.opd.form', [
            'aksi' => 'Tambah',
            'back' => Str::contains(url()->previous(), ['dataopd/tambah', 'dataopd/edit'])? '/dataopd': url()->previous(),
        ]);
    }

	public function do_tambah(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'opd_nama' => 'required',
                'opd_lokasi' => 'required'
            ],[
                'opd_nama.required'  => 'Nama OPD tidak boleh kosong',
                'opd_lokasi.required'  => 'Lokasi tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
			$data = new Opd();
			$data->opd_nama = $req->get('opd_nama');
			$data->opd_lokasi = $req->get('opd_lokasi');
            $data->operator = Auth::user()->pengguna_nama;
            $data->save();
            toast('Berhasil menambah OPD '.$req->get('opd_nama'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('dataopd'));
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function edit(Request $req)
	{
        return view('pages.datamaster.opd.form', [
            'aksi' => 'Edit',
            'data' => Opd::findOrFail($req->nama),
            'back' => Str::contains(url()->previous(), ['dataopd/tambah', 'dataopd/edit'])? '/dataopd': url()->previous(),
        ]);
    }

	public function do_edit(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'opd_nama' => 'required',
                'opd_lokasi' => 'required'
            ],[
                'opd_nama.required'  => 'Nama OPD tidak boleh kosong',
                'opd_lokasi.required'  => 'Lokasi tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
			$data = Opd::findOrFail($req->get('id'));
			$data->opd_nama = $req->get('opd_nama');
			$data->opd_lokasi = $req->get('opd_lokasi');
            $data->operator = Auth::user()->pengguna_nama;
            $data->save();

            toast('Berhasil mengedit OPD '.$req->get('opd_nama'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('dataopd'));
        }catch(\Exception $e){
            alert()->error('Edit Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function hapus(Request $req)
	{
		try{
            $data = Opd::findOrFail($req->nama);
            $data->delete();
            toast('Berhasil menghapus data '.$data->opd_nama, 'success')->autoClose(2000);
		}catch(\Exception $e){
            alert()->error('Hapus Data', $e->getMessage());
		}
	}

	public function cari(Request $req)
	{
        $data = Opd::where(function($q) use ($req){
            $q->where('opd_nama', 'like', '%'.$req->cari.'%');
        })->orderBy('opd_nama', 'asc')->get();

		return $data;
    }
}
