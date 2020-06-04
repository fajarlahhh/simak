<?php

namespace App\Http\Controllers;

use PDF;
use App\Salam;
use App\Edaran;
use App\KopSurat;
use App\Pengguna;
use App\Penomoran;
use Carbon\Carbon;
use App\EdaranLampiran;
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
            'edit' => 1,
            'data' => null,
            'kepada' => null,
            'pengguna' => Pengguna::whereHas('jabatan', function ($q){
                $q->where('jabatan_pimpinan', 1);
            })->get(),
            'back' => Str::contains(url()->previous(), ['edaran/tambah', 'edaran/edit'])? '/edaran': url()->previous(),
        ]);
	}

	public function do_tambah(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'edaran_tanggal' => 'required'
            ],[
                'edaran_tanggal.required'  => 'Tanggal Surat tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
            $kepada = $req->get('edaran_kepada_awal')."<ol>";
            foreach ($req->get('edaran_kepada_tujuan') as $key => $value) {
                $kepada .= "<li>".$value."</li>";
            }
            $kepada .= "</ol>".$req->get('edaran_kepada_akhir');

            $format = Penomoran::where('penomoran_jenis', 'edaran')->first()->penomoran_format;
            $urutan = env('EDARAN');
            $data = Edaran::withTrashed()->whereRaw('year(edaran_tanggal)='.date('Y'))->orderBy('urutan', 'desc')->get();
            if($data->count() > 0){
                $urutan = $data->first()->urutan;
            }
            $cari  = array('$urut$','$bidang$','$tahun$');
            $ganti = array($urutan + 1, Auth::user()->bidang_nama, date('Y'));
            $nomor = str_replace($cari, $ganti, $format);

            $salam = Salam::all()->first();
            $kop = KopSurat::all()->first()->kop_isi;
            if($req->get('edaran_pejabat')){
                $pengguna = Pengguna::findOrFail($req->get('edaran_pejabat'));
            }

			$data = new Edaran();
            $data->edaran_nomor = $nomor;
			$data->edaran_tanggal = Carbon::parse($req->get('edaran_tanggal'))->format('Y-m-d');
			$data->edaran_sifat = $req->get('edaran_sifat');
			$data->edaran_perihal = $req->get('edaran_perihal');
			$data->edaran_lampiran = $req->get('edaran_lampiran');
			$data->edaran_kepada = $kepada;
            $data->edaran_isi = $req->get('edaran_isi');
            if($req->get('edaran_pejabat')){
                $data->edaran_ttd = $req->get('edaran_jenis_ttd') == 2? $pengguna->gambar->gambar_lokasi: 1;
                $data->edaran_pejabat = "<strong>".$pengguna->pengguna_nama."</strong><br>".$pengguna->pengguna_pangkat."<br>NIP. ".$pengguna->pengguna_nip;
                $data->jabatan_nama = $pengguna->jabatan_nama;
            }
            $data->edaran_tembusan = $req->get('edaran_tembusan');
            $data->salam_pembuka = $salam->salam_pembuka;
            $data->salam_penutup = $salam->salam_penutup;
            $data->kop_isi = $kop;
            $data->urutan = $urutan + 1;
			$data->operator = Auth::user()->pengguna_nama;
            $data->save();

            if($req->hasFile('lampiran'))
            {
                foreach ($req->file('lampiran') as $file) {
                    $ext = $file->getClientOriginalExtension();
                    $nama_file = time().Str::random().".".$ext;
                    $file->move(public_path('uploads/edaran/gambar'), $nama_file);
                    EdaranLampiran::create([
                        'edaran_nomor' => $nomor,
                        'file' => '/uploads/edaran/gambar/'.$nama_file
                        ]);
                }
            }

            toast('Berhasil menambah edaran '.$req->get('edaran_nomor'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('edaran'));
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function edit(Request $req)
	{
        $data = Edaran::with('lampiran')->findOrFail($req->no);
        $kepada = null;
        if($data && $data->edaran_kepada){
            $kepada = explode("<ol>", $data->edaran_kepada);
        }
        return view('pages.suratkeluar.edaran.form', [
            'aksi' => 'Edit',
            'edit' => 1,
            'awal' => $kepada? $kepada[0]: null,
            'tujuan' => $kepada? str_replace(array("<li>", "</li>"), array("", ""), explode("</li><li>", explode("</ol>", $kepada[1])[0])): null,
            'akhir' => $kepada? explode("</ol>", $kepada[1])[1]: null,
            'i' => 0,
            'data' => $data,
            'pengguna' => Pengguna::whereHas('jabatan', function ($q) use ($req){
                $q->where('jabatan_struktural', 1);
            })->get(),
            'back' => Str::contains(url()->previous(), ['edaran/tambah', 'edaran/edit'])? '/edaran': url()->previous(),
        ]);
	}

	public function edit_isi(Request $req)
	{
        return view('pages.suratkeluar.edaran.form', [
            'aksi' => 'Edit',
            'edit' => 2,
            'data' => Edaran::with('lampiran')->findOrFail($req->no),
            'pengguna' => Pengguna::whereHas('jabatan', function ($q) use ($req){
                $q->where('jabatan_struktural', 1);
            })->get(),
            'back' => Str::contains(url()->previous(), ['edaran/tambah', 'edaran/edit'])? '/edaran': url()->previous(),
        ]);
	}

	public function do_edit(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'edaran_nomor' => 'required',
            ],[
                'edaran_nomor.required'  => 'Nomor tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
            $salam = Salam::all()->first();
            $kop = KopSurat::all()->first()->kop_isi;
            if($req->get('edaran_pejabat')){
                $pengguna = Pengguna::findOrFail($req->get('edaran_pejabat'));
            }

            $kepada = $req->get('edaran_kepada_awal')."<ol>";
            foreach ($req->get('edaran_kepada_tujuan') as $key => $value) {
                $kepada .= "<li>".$value."</li>";
            }
            $kepada .= "</ol>".$req->get('edaran_kepada_akhir');

			$data = Edaran::findOrFail($req->get('edaran_nomor'));
			$data->edaran_tanggal = Carbon::parse($req->get('edaran_tanggal'))->format('Y-m-d');
			$data->edaran_sifat = $req->get('edaran_sifat');
			$data->edaran_perihal = $req->get('edaran_perihal');
			$data->edaran_lampiran = $req->get('edaran_lampiran');
			$data->edaran_kepada = $kepada;
			$data->edaran_isi = $req->get('edaran_isi');
            if($req->get('edaran_pejabat')){
                $data->edaran_ttd = $req->get('edaran_jenis_ttd') == 2? $pengguna->gambar->gambar_lokasi: 1;
                $data->edaran_pejabat = "<strong>".$pengguna->pengguna_nama."</strong><br>".$pengguna->pengguna_pangkat."<br>NIP. ".$pengguna->pengguna_nip;
                $data->jabatan_nama = $pengguna->jabatan_nama;
            }
            $data->edaran_tembusan = $req->get('edaran_tembusan');
            $data->salam_pembuka = $salam->salam_pembuka;
            $data->salam_penutup = $salam->salam_penutup;
            $data->kop_isi = $kop;
            $data->operator = Auth::user()->pengguna_nama;
            $data->save();

            if($req->hasFile('lampiran'))
            {
                foreach ($req->file('lampiran') as $file) {
                    $ext = $file->getClientOriginalExtension();
                    $nama_file = time().Str::random().".".$ext;
                    $file->move(public_path('uploads/edaran/gambar'), $nama_file);
                    EdaranLampiran::create([
                        'edaran_nomor' => $req->get('edaran_nomor'),
                        'file' => '/uploads/edaran/gambar/'.$nama_file
                        ]);
                }
            }

            toast('Berhasil mengedit edaran '.$req->get('edaran_nomor'), 'success')->autoClose(2000);
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

	public function hapus_lampiran(Request $req)
	{
		try{
            $data = EdaranLampiran::findOrFail($req->get('file'));
            $data->delete();
            File::delete(public_path($req->get('file')));
            return 1;
		}catch(\Exception $e){
            return 0;
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

	public function cetak(Request $req)
	{
        $id = $req->get('no');
        try{
            $data = Edaran::withTrashed()->findOrFail($id);
            $pdf = PDF::loadView('layouts.print-surat', [
                'halaman' => 'pages.suratkeluar.edaran.cetak',
                'data' => $data,
                'judul' => 'Nomor Edaran '.$data->edaran_nomor
            ], [], [
                'format' => 'A4'
            ]);
            return $pdf->stream('EDARAN '.$req->get('no'));
        }catch(\Exception $e){
            $error = $e->getMessage();
        }
    }
}
