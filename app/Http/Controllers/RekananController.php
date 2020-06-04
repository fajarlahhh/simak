<?php

namespace App\Http\Controllers;

use App\Rekanan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RekananController extends Controller
{


    public function index(Request $req)
	{
        $data = Rekanan::where(function($q) use ($req){
            $q->where('rekanan_nama', 'like', '%'.$req->cari.'%');
        })->orderBy('rekanan_nama')->paginate(10);

        $data->appends(['cari' => $req->cari]);
        return view('pages.datamaster.rekanan.index', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'cari' => $req->cari
        ]);
    }

	public function tambah(Request $req)
	{
        return view('pages.datamaster.rekanan.form', [
            'aksi' => 'Tambah',
            'back' => Str::contains(url()->previous(), ['datarekanan/tambah', 'datarekanan/edit'])? '/datarekanan': url()->previous(),
        ]);
    }

	public function do_tambah(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'rekanan_nama' => 'required'
            ],[
                'rekanan_nama.required'  => 'Nama Rekanan tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
			$data = new Rekanan();
			$data->rekanan_nama = $req->get('rekanan_nama');
            $data->save();
            toast('Berhasil menambah rekanan '.$req->get('rekanan_nama'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('datarekanan'));
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function edit(Request $req)
	{
        return view('pages.datamaster.rekanan.form', [
            'aksi' => 'Edit',
            'data' => Rekanan::findOrFail($req->nama),
            'back' => Str::contains(url()->previous(), ['datarekanan/tambah', 'datarekanan/edit'])? '/datarekanan': url()->previous(),
        ]);
    }

	public function do_edit(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'rekanan_nama' => 'required'
            ],[
                'rekanan_nama.required'  => 'Nama Rekanan tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
			$data = Rekanan::findOrFail($req->get('id'));
			$data->rekanan_nama = $req->get('rekanan_nama');
            $data->save();

            toast('Berhasil mengedit rekanan '.$req->get('rekanan_nama'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('datarekanan'));
        }catch(\Exception $e){
            alert()->error('Edit Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function hapus(Request $req)
	{
		try{
            $data = Rekanan::findOrFail($req->nama);
            $data->delete();
            toast('Berhasil menghapus rekanan '.$data->rekanan_nama, 'success')->autoClose(2000);
		}catch(\Exception $e){
            alert()->error('Hapus Data', $e->getMessage());
		}
	}

	public function cari(Request $req)
	{
        $data = Rekanan::where(function($q) use ($req){
            $q->where('rekanan_nama', 'like', '%'.$req->cari.'%');
        })->orderBy('rekanan_nama', 'asc')->get();

		return $data;
    }
}
