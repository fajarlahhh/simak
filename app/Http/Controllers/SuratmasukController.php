<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\SuratMasuk;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\SuratMasukEvent;
use App\Events\SuratMasukTerinput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SuratmasukController extends Controller
{
    //
	public function index(Request $req)
	{
        $data = SuratMasuk::where(function($q) use ($req){
            $q->where('surat_masuk_asal', 'like', '%'.$req->cari.'%')->orWhere('surat_masuk_perihal', 'like', '%'.$req->cari.'%')->orWhere('surat_masuk_keterangan', 'like', '%'.$req->cari.'%')->orWhere('surat_masuk_nomor', 'like', '%'.$req->cari.'%');
        })->orderBy('surat_masuk_tanggal_masuk', 'desc');

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

        $data->appends(['tipe' => $req->tipe, 'cari' => $req->tipe]);
        return view('pages.suratmasuk.index', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'tipe' => $req->tipe,
            'cari' => $req->cari
        ]);
    }

	public function tambah()
	{
        return view('pages.suratmasuk.form', [
            'aksi' => 'Tambah',
            'back' => Str::contains(url()->previous(), ['suratmasuk/tambah', 'suratmasuk/edit'])? '/suratmasuk': url()->previous(),
        ]);
	}

	public function do_tambah(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'surat_masuk_nomor' => 'required',
                'surat_masuk_tanggal_masuk' => 'required',
                'surat_masuk_tanggal_surat' => 'required',
                'surat_masuk_perihal' => 'required',
                'surat_masuk_asal' => 'required',
                'file' => 'required|mimes:pdf'
            ],[
                'surat_masuk_nomor.required'  => 'Nomor Surat tidak boleh kosong',
                'surat_masuk_tanggal_masuk.required'  => 'Tanggal Masuk tidak boleh kosong',
                'surat_masuk_tanggal_surat.required'  => 'Tanggal Surat tidak boleh kosong',
                'surat_masuk_perihal.required'  => 'Perihal tidak boleh kosong',
                'surat_masuk_asal.required'  => 'Asal tidak boleh kosong',
                'file.required'  => 'File tidak boleh kosong'
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
            $file->move(public_path('uploads/suratmasuk'), $nama_file);

			$data = new SuratMasuk();
			$data->surat_masuk_nomor = $req->get('surat_masuk_nomor');
			$data->surat_masuk_tanggal_masuk = Carbon::parse($req->get('surat_masuk_tanggal_masuk'))->format('Y-m-d');
			$data->surat_masuk_tanggal_surat = Carbon::parse($req->get('surat_masuk_tanggal_surat'))->format('Y-m-d');
			$data->surat_masuk_perihal = $req->get('surat_masuk_perihal');
			$data->surat_masuk_asal = $req->get('surat_masuk_asal');
			$data->surat_masuk_keterangan = $req->get('surat_masuk_keterangan');
            $data->file = 'uploads/suratmasuk/'.$nama_file;
			$data->operator = Auth::user()->pengguna_nama;
            $data->save();

            toast('Berhasil menambah surat masuk '.$req->get('surat_masuk_nomor'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('suratmasuk'));
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function edit(Request $req)
	{
        return view('pages.suratmasuk.form', [
            'aksi' => 'Edit',
            'data' => SuratMasuk::findOrFail($req->no),
            'back' => Str::contains(url()->previous(), ['suratmasuk/tambah', 'suratmasuk/edit'])? '/suratmasuk': url()->previous(),
        ]);
	}

	public function do_edit(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'surat_masuk_nomor' => 'required',
                'surat_masuk_tanggal_masuk' => 'required',
                'surat_masuk_tanggal_surat' => 'required',
                'surat_masuk_perihal' => 'required',
                'surat_masuk_asal' => 'required'
            ],[
                'surat_masuk_nomor.required'  => 'Nomor Surat tidak boleh kosong',
                'surat_masuk_tanggal_masuk.required'  => 'Tanggal Masuk tidak boleh kosong',
                'surat_masuk_tanggal_surat.required'  => 'Tanggal Surat tidak boleh kosong',
                'surat_masuk_perihal.required'  => 'Perihal tidak boleh kosong',
                'surat_masuk_asal.required'  => 'Asal tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
			$data = SuratMasuk::findOrFail($req->get('ID'));
            if($req->file('file')){
                if($req->get('file_old')){
                    File::delete(public_path($req->get('file_old')));
                }
                $file = $req->file('file');

                $ext = $file->getClientOriginalExtension();
                $nama_file = time().Str::random().".".$ext;
                $file->move(public_path('uploads/suratmasuk'), $nama_file);
                $data->file = 'uploads/suratmasuk/'.$nama_file;
            }

			$data->surat_masuk_nomor = $req->get('surat_masuk_nomor');
			$data->surat_masuk_tanggal_masuk = Carbon::parse($req->get('surat_masuk_tanggal_masuk'))->format('Y-m-d');
			$data->surat_masuk_tanggal_surat = Carbon::parse($req->get('surat_masuk_tanggal_surat'))->format('Y-m-d');
			$data->surat_masuk_perihal = $req->get('surat_masuk_perihal');
			$data->surat_masuk_asal = $req->get('surat_masuk_asal');
			$data->surat_masuk_keterangan = $req->get('surat_masuk_keterangan');
			$data->operator = Auth::user()->pengguna_nama;
            $data->save();

            toast('Berhasil menambah surat masuk '.$req->get('surat_masuk_nomor'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('suratmasuk'));
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function hapus(Request $req)
	{
		try{
            SuratMasuk::findOrFail($req->get('no'))->delete();
            toast('Berhasil menghapus data', 'success')->autoClose(2000);
		}catch(\Exception $e){
            alert()->error('Hapus Data', $e->getMessage());
		}
	}

	public function restore(Request $req)
	{
		try{
            SuratMasuk::withTrashed()->findOrFail($req->get('no'))->restore();
            toast('Berhasil mengembalikan data', 'success')->autoClose(2000);
		}catch(\Exception $e){
            alert()->error('Restore Data', $e->getMessage());
		}
	}
}
