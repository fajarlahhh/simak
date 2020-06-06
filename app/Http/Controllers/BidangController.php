<?php

namespace App\Http\Controllers;

use App\Bidang;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BidangController extends Controller
{


    public function index(Request $req)
	{
        $data = Bidang::where(function($q) use ($req){
            $q->where('bidang_nama', 'like', '%'.$req->cari.'%');
        })->orderBy('bidang_nama')->paginate(10);

        $data->appends(['cari' => $req->cari]);
        return view('pages.datamaster.bidang.index', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'cari' => $req->cari
        ]);
    }

	public function tambah(Request $req)
	{
        return view('pages.datamaster.bidang.form', [
            'aksi' => 'Tambah',
            'back' => Str::contains(url()->previous(), ['databidang/tambah', 'databidang/edit'])? '/databidang': url()->previous(),
        ]);
    }

	public function do_tambah(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'bidang_nama' => 'required',
                'bidang_alias' => 'required'
            ],[
                'bidang_nama.required'  => 'Nama Bidang tidak boleh kosong',
                'bidang_alias.required'  => 'Alias Bidang tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
			$data = new Bidang();
			$data->bidang_nama = $req->get('bidang_nama');
			$data->bidang_alias = $req->get('bidang_alias');
            $data->operator = Auth::user()->pengguna_nama;
            $data->save();
            toast('Berhasil menambah bidang '.$req->get('bidang_nama'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('databidang'));
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function edit($id)
	{
        return view('pages.datamaster.bidang.form', [
            'aksi' => 'Edit',
            'data' => Bidang::findOrFail($id),
            'back' => Str::contains(url()->previous(), ['databidang/tambah', 'databidang/edit'])? '/databidang': url()->previous(),
        ]);
    }

	public function do_edit(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'bidang_nama' => 'required',
                'bidang_alias' => 'required'
            ],[
                'bidang_nama.required'  => 'Nama Bidang tidak boleh kosong',
                'bidang_alias.required'  => 'Alias Bidang tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
			$data = Bidang::findOrFail($req->get('id'));
			$data->bidang_nama = $req->get('bidang_nama');
			$data->bidang_alias = $req->get('bidang_alias');
            $data->operator = Auth::user()->pengguna_nama;
            $data->save();

            toast('Berhasil mengedit bidang '.$req->get('bidang_nama'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('databidang'));
        }catch(\Exception $e){
            alert()->error('Edit Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function hapus($id)
	{
		try{
            $data = Bidang::findOrFail($id);
            $data->delete();
            toast('Berhasil menghapus data '.$data->bidang_nama, 'success')->autoClose(2000);
		}catch(\Exception $e){
            alert()->error('Hapus Data', $e->getMessage());
		}
	}
}
