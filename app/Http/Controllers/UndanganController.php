<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Undangan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class UndanganController extends Controller
{
    //
	public function index(Request $req)
	{
        $data = Undangan::where(function($q) use ($req){
            $q->where('undangan_asal', 'like', '%'.$req->cari.'%')->orWhere('undangan_perihal', 'like', '%'.$req->cari.'%')->orWhere('undangan_keterangan', 'like', '%'.$req->cari.'%')->orWhere('undangan_nomor', 'like', '%'.$req->cari.'%');
        })->orderBy('undangan_tanggal_undangan', 'desc');

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
        return view('pages.suratkeluar.undangan.index', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'tipe' => $req->tipe,
            'cari' => $req->cari
        ]);
    }

	public function tambah()
	{
        return view('pages.suratkeluar.undangan.form', [
            'aksi' => 'Tambah',
            'back' => Str::contains(url()->previous(), ['undangan/tambah', 'undangan/edit'])? '/undangan': url()->previous(),
        ]);
	}

	public function do_tambah(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'undangan_nomor' => 'required',
                'undangan_tanggal_undangan' => 'required',
                'undangan_tanggal_surat' => 'required',
                'undangan_perihal' => 'required',
                'undangan_asal' => 'required',
                'file' => 'required|mimes:pdf'
            ],[
                'undangan_nomor.required'  => 'Nomor Surat tidak boleh kosong',
                'undangan_tanggal_undangan.required'  => 'Tanggal Masuk tidak boleh kosong',
                'undangan_tanggal_surat.required'  => 'Tanggal Surat tidak boleh kosong',
                'undangan_perihal.required'  => 'Perihal tidak boleh kosong',
                'undangan_asal.required'  => 'Asal tidak boleh kosong',
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
            $file->move(public_path('uploads/suratkeluar.undangan'), $nama_file);

			$data = new Undangan();
			$data->undangan_nomor = $req->get('undangan_nomor');
			$data->undangan_tanggal_undangan = Carbon::parse($req->get('undangan_tanggal_undangan'))->format('Y-m-d');
			$data->undangan_tanggal_surat = Carbon::parse($req->get('undangan_tanggal_surat'))->format('Y-m-d');
			$data->undangan_perihal = $req->get('undangan_perihal');
			$data->undangan_asal = $req->get('undangan_asal');
			$data->undangan_keterangan = $req->get('undangan_keterangan');
            $data->file = 'uploads/suratkeluar.undangan/'.$nama_file;
			$data->operator = Auth::user()->pengguna_nama;
            $data->save();

            toast('Berhasil menambah surat masuk '.$req->get('undangan_nomor'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('suratkeluar.undangan'));
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function edit(Request $req)
	{
        return view('pages.suratkeluar.undangan.form', [
            'aksi' => 'Edit',
            'data' => Undangan::findOrFail($req->no),
            'back' => Str::contains(url()->previous(), ['undangan/tambah', 'undangan/edit'])? '/undangan': url()->previous(),
        ]);
	}

	public function do_edit(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'undangan_nomor' => 'required',
                'undangan_tanggal_undangan' => 'required',
                'undangan_tanggal_surat' => 'required',
                'undangan_perihal' => 'required',
                'undangan_asal' => 'required'
            ],[
                'undangan_nomor.required'  => 'Nomor Surat tidak boleh kosong',
                'undangan_tanggal_undangan.required'  => 'Tanggal Masuk tidak boleh kosong',
                'undangan_tanggal_surat.required'  => 'Tanggal Surat tidak boleh kosong',
                'undangan_perihal.required'  => 'Perihal tidak boleh kosong',
                'undangan_asal.required'  => 'Asal tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
			$data = Undangan::findOrFail($req->get('ID'));
			$data->undangan_nomor = $req->get('undangan_nomor');
			$data->undangan_tanggal_undangan = Carbon::parse($req->get('undangan_tanggal_undangan'))->format('Y-m-d');
			$data->undangan_tanggal_surat = Carbon::parse($req->get('undangan_tanggal_surat'))->format('Y-m-d');
			$data->undangan_perihal = $req->get('undangan_perihal');
			$data->undangan_asal = $req->get('undangan_asal');
			$data->undangan_keterangan = $req->get('undangan_keterangan');
            if($req->file('file')){
                if($req->get('file_old')){
                    File::delete(public_path($req->get('file_old')));
                }
                $file = $req->file('file');

                $ext = $file->getClientOriginalExtension();
                $nama_file = time().Str::random().".".$ext;
                $file->move(public_path('uploads/suratkeluar.undangan'), $nama_file);
                $data->file = 'uploads/suratkeluar.undangan/'.$nama_file;
            }
			$data->operator = Auth::user()->pengguna_nama;
            $data->save();

            toast('Berhasil menambah surat masuk '.$req->get('undangan_nomor'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('suratkeluar.undangan'));
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function hapus(Request $req)
	{
		try{
            Undangan::findOrFail($req->get('no'))->delete();
            toast('Berhasil menghapus data', 'success')->autoClose(2000);
		}catch(\Exception $e){
            alert()->error('Hapus Data', $e->getMessage());
		}
	}

	public function restore(Request $req)
	{
		try{
            Undangan::withTrashed()->findOrFail($req->get('no'))->restore();
            toast('Berhasil mengembalikan data', 'success')->autoClose(2000);
		}catch(\Exception $e){
            alert()->error('Restore Data', $e->getMessage());
		}
	}
}
