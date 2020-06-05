<?php

namespace App\Http\Controllers;

use App\Bidang;
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
        return view('pages.datamaster.jabatan.index', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'cari' => $req->cari
        ]);
    }

	public function tambah(Request $req)
	{
        return view('pages.datamaster.jabatan.form', [
            'aksi' => 'Tambah',
            'bidang' => Bidang::all(),
            'jabatan' => Jabatan::where('jabatan_struktural', 1)->get(),
            'back' => Str::contains(url()->previous(), ['datajabatan/tambah', 'datajabatan/edit'])? '/datajabatan': url()->previous(),
        ]);
    }

	public function do_tambah(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'jabatan_nama' => 'required'
            ],[
                'jabatan_nama.required'  => 'Nama Jabatan tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

            $parent = $req->get('jabatan_parent')? $req->get('jabatan_parent'): null;
            $silsilah = null;
            if($parent){
                $silsilah = (Jabatan::findOrFail($parent)->jabatan_silsilah? Jabatan::findOrFail($parent)->jabatan_silsilah.";": "").$parent;
            }

			$data = new Jabatan();
			$data->jabatan_nama = $req->get('jabatan_nama');
			$data->jabatan_parent = $parent;
			$data->jabatan_silsilah = $silsilah;
			$data->jabatan_pimpinan = $req->get('jabatan_pimpinan')? 1: 0;
			$data->jabatan_struktural = $req->get('jabatan_struktural')? 1: 0;
			$data->jabatan_verifikator = $req->get('jabatan_verifikator')? 1: 0;
			$data->bidang_id = $req->get('bidang_id');
            $data->operator = Auth::user()->pengguna_nama;
            $data->save();
            toast('Berhasil menambah jabatan '.$req->get('jabatan_nama'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('datajabatan'));

	}

	public function edit($id)
	{
        $data = Jabatan::findOrFail($id);
        return view('pages.datamaster.jabatan.form', [
            'aksi' => 'Edit',
            'jabatan' => Jabatan::where('jabatan_struktural', 1)->where('jabatan_nama', '!=', $data->jabatan_nama)->get(),
            'data' => $data,
            'bidang' => Bidang::all(),
            'back' => Str::contains(url()->previous(), ['datajabatan/tambah', 'datajabatan/edit'])? '/datajabatan': url()->previous(),
        ]);
    }

	public function do_edit(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'jabatan_nama' => 'required'
            ],[
                'jabatan_nama.required'  => 'Nama Jabatan tidak boleh kosong'
            ]
        );


        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
            $parent = $req->get('jabatan_parent')? $req->get('jabatan_parent'): null;
            $silsilah = null;
            if($parent){
                $silsilah = (Jabatan::findOrFail($parent)->jabatan_silsilah? Jabatan::findOrFail($parent)->jabatan_silsilah.";": "").$parent;
            }

			$data = Jabatan::findOrFail($req->get('id'));
			$data->jabatan_nama = $req->get('jabatan_nama');
			$data->jabatan_parent = $parent;
			$data->jabatan_silsilah = $silsilah;
			$data->jabatan_pimpinan = $req->get('jabatan_pimpinan')? 1: 0;
			$data->jabatan_struktural = $req->get('jabatan_struktural')? 1: 0;
			$data->jabatan_verifikator = $req->get('jabatan_verifikator')? 1: 0;
			$data->bidang_id = $req->get('bidang_id');
            $data->operator = Auth::user()->pengguna_nama;
            $data->save();

            toast('Berhasil mengedit jabatan '.$req->get('jabatan_nama'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('datajabatan'));
        }catch(\Exception $e){
            alert()->error('Edit Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function hapus($id)
	{
		try{
            $data = Jabatan::findOrFail($id);
            $data->delete();
            toast('Berhasil menghapus jabatan '.$data->jabatan_nama, 'success')->autoClose(2000);
		}catch(\Exception $e){
            alert()->error('Hapus Data', $e->getMessage());
		}
	}
}
