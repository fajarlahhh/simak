<?php

namespace App\Http\Controllers;

use App\Gambar;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class GambarController extends Controller
{
    //
    public function __construct()
	{
		$this->middleware('auth');
    }

    public function index(Request $req)
	{
        $data = Gambar::where(function($q) use ($req){
            $q->where('gambar_nama', 'like', '%'.$req->cari.'%');
        })->orderBy('gambar_nama')->paginate(10);

        $data->appends(['cari' => $req->cari]);
        return view('pages.datamaster.gambar.index', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'cari' => $req->cari
        ]);
    }

	public function tambah(Request $req)
	{
        return view('pages.datamaster.gambar.form', [
            'aksi' => 'Tambah',
            'back' => Str::contains(url()->previous(), ['gambar/tambah', 'gambar/edit'])? '/gambar': url()->previous(),
        ]);
    }

	public function do_tambah(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'gambar_nama' => 'required',
                'gambar_lokasi' => 'required'
            ],[
                'gambar_nama.required'  => 'Nama Gambar tidak boleh kosong',
                'gambar_lokasi.required'  => 'Upload Gambar tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
            $gambar = $req->file('gambar_lokasi');

            $ext = $gambar->getClientOriginalExtension();
            $nama_gambar = time().Str::random().".".$ext;
            $gambar->move(public_path('uploads/gambar'), $nama_gambar);

			$data = new Gambar();
			$data->gambar_nama = $req->get('gambar_nama');
			$data->gambar_lokasi = 'uploads/gambar/'.$nama_gambar;
            $data->save();
            toast('Berhasil menambah gambar '.$req->get('gambar_nama'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('gambar'));
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function hapus(Request $req)
	{
		try{
            $data = Gambar::findOrFail($req->nama);
            File::delete(public_path($data->gambar_lokasi));
            $data->delete();
            toast('Berhasil menghapus gambar '.$data->gambar_nama, 'success')->autoClose(2000);
		}catch(\Exception $e){
            alert()->error('Hapus Data', $e->getMessage());
		}
	}
}
