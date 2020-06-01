<?php

namespace App\Http\Controllers;

use App\Edaran;
use App\KopSurat;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class EdaranController extends Controller
{
    //
	public function index(Request $req)
	{
        $data = Edaran::where(function($q) use ($req){
            $q->where('edaran_sifat', 'like', '%'.$req->cari.'%')->orWhere('edaran_perihal', 'like', '%'.$req->cari.'%')->orWhere('edaran_nomor', 'like', '%'.$req->cari.'%');
        })->orderBy('edaran_tanggal', 'desc');

        switch ($req->tipe) {
            case '1':
                $data = $data->onlyTrashed();
                break;
            case '2':
                $data = $data->withTrashed();
                break;

            default:
                # code...
                break;
        }

        $data = $data->paginate(10);

        $data->appends(['cari' => $req->tipe, 'cari' => $req->tipe]);
        return view('pages.suratkeluar.edaran.index', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'tipe' => $req->tipe,
            'cari' => $req->cari
        ]);
    }

	public function tambah()
	{
        return view('pages.suratkeluar.edaran.form', [
            'aksi' => 'Tambah',
            'back' => Str::contains(url()->previous(), ['edaran/tambah', 'edaran/edit'])? '/edaran': url()->previous(),
        ]);
	}

	public function do_tambah(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'edaran_nomor' => 'required',
                'surat_masuk_tanggal_masuk' => 'required',
                'edaran_tanggal' => 'required',
                'edaran_perihal' => 'required',
                'edaran_sifat' => 'required'
            ],[
                'edaran_nomor.required'  => 'Nomor Surat tidak boleh kosong',
                'surat_masuk_tanggal_masuk.required'  => 'Tanggal Masuk tidak boleh kosong',
                'edaran_tanggal.required'  => 'Tanggal Surat tidak boleh kosong',
                'edaran_perihal.required'  => 'Perihal tidak boleh kosong',
                'edaran_sifat.required'  => 'Asal tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
            $file = $req->file('file');

            $ext = $file->getClientOriginalExtension();
            $nama_file = time().Str::random().".".$ext;
            $file->move(public_path('uploads/edaran'), $nama_file);

			$data = new Edaran();
			$data->edaran_nomor = $req->get('edaran_nomor');
			$data->surat_masuk_tanggal_masuk = Carbon::parse($req->get('surat_masuk_tanggal_masuk'))->format('Y-m-d');
			$data->edaran_tanggal = Carbon::parse($req->get('edaran_tanggal'))->format('Y-m-d');
			$data->edaran_perihal = $req->get('edaran_perihal');
			$data->edaran_sifat = $req->get('edaran_sifat');
			$data->surat_masuk_keterangan = $req->get('surat_masuk_keterangan');
            $data->file = 'uploads/edaran/'.$nama_file;
			$data->operator = Auth::user()->pengguna_nama;
            $data->save();

            toast('Berhasil menambah surat masuk '.$req->get('edaran_nomor'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('edaran'));
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function edit(Request $req)
	{
        return view('pages.suratkeluar.edaran.form', [
            'aksi' => 'Edit',
            'data' => Edaran::findOrFail($req->no),
            'back' => Str::contains(url()->previous(), ['edaran/tambah', 'edaran/edit'])? '/edaran': url()->previous(),
        ]);
	}

	public function do_edit(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'edaran_nomor' => 'required',
                'surat_masuk_tanggal_masuk' => 'required',
                'edaran_tanggal' => 'required',
                'edaran_perihal' => 'required',
                'edaran_sifat' => 'required'
            ],[
                'edaran_nomor.required'  => 'Nomor Surat tidak boleh kosong',
                'surat_masuk_tanggal_masuk.required'  => 'Tanggal Masuk tidak boleh kosong',
                'edaran_tanggal.required'  => 'Tanggal Surat tidak boleh kosong',
                'edaran_perihal.required'  => 'Perihal tidak boleh kosong',
                'edaran_sifat.required'  => 'Asal tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
			$data = Edaran::findOrFail($req->get('ID'));
			$data->edaran_nomor = $req->get('edaran_nomor');
			$data->surat_masuk_tanggal_masuk = Carbon::parse($req->get('surat_masuk_tanggal_masuk'))->format('Y-m-d');
			$data->edaran_tanggal = Carbon::parse($req->get('edaran_tanggal'))->format('Y-m-d');
			$data->edaran_perihal = $req->get('edaran_perihal');
			$data->edaran_sifat = $req->get('edaran_sifat');
			$data->surat_masuk_keterangan = $req->get('surat_masuk_keterangan');
            if($req->file('file')){
                if($req->get('file_old')){
                    File::delete(public_path($req->get('file_old')));
                }
                $file = $req->file('file');

                $ext = $file->getClientOriginalExtension();
                $nama_file = time().Str::random().".".$ext;
                $file->move(public_path('uploads/edaran'), $nama_file);
                $data->file = 'uploads/edaran/'.$nama_file;
            }
			$data->operator = Auth::user()->pengguna_nama;
            $data->save();

            toast('Berhasil menambah surat masuk '.$req->get('edaran_nomor'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('edaran'));
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function hapus(Request $req)
	{
		try{
            Edaran::findOrFail($req->get('no'))->delete();
            toast('Berhasil menghapus data', 'success')->autoClose(2000);
		}catch(\Exception $e){
            alert()->error('Hapus Data', $e->getMessage());
		}
	}

	public function restore(Request $req)
	{
		try{
            Edaran::withTrashed()->findOrFail($req->get('no'))->restore();
            toast('Berhasil mengembalikan data', 'success')->autoClose(2000);
		}catch(\Exception $e){
            alert()->error('Restore Data', $e->getMessage());
		}
	}
}
