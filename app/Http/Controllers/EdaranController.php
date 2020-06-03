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
            'data' => null,
            'pengguna' => Pengguna::whereHas('jabatan', function ($q){
                $q->where('jabatan_struktural', 1);
            })->get(),
            'back' => Str::contains(url()->previous(), ['edaran/tambah', 'edaran/edit'])? '/edaran': url()->previous(),
        ]);
	}

	public function do_tambah(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'edaran_tanggal' => 'required',
                'edaran_perihal' => 'required',
                'edaran_isi' => 'required',
                'edaran_kepada' => 'required',
                'edaran_jenis_ttd' => 'required',
                'edaran_pejabat' => 'required'
            ],[
                'edaran_tanggal.required'  => 'Tanggal Surat tidak boleh kosong',
                'edaran_perihal.required'  => 'Perihal tidak boleh kosong',
                'edaran_isi.required'  => 'Isi tidak boleh kosong',
                'edaran_kepada.required'  => 'Tujuan tidak boleh kosong',
                'edaran_jenis_ttd.required'  => 'Jenis Tanda Tangan tidak boleh kosong',
                'edaran_pejabat.required'  => 'Tanda Tangan tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
            $format = Penomoran::where('penomoran_jenis', 'edaran')->first()->penomoran_format;
            $urutan = env('EDARAN');
            $data = Edaran::whereRaw('year(edaran_tanggal)='.date('Y'))->orderBy('urutan', 'desc')->get();
            if($data->count() > 0){
                $urutan = $data->first()->urutan;
            }
            $cari  = array('$urut$','$bidang$','$tahun$');
            $ganti = array($urutan + 1, Auth::user()->bidang_nama, date('Y'));
            $nomor = str_replace($cari, $ganti, $format);

            $salam = Salam::all()->first();
            $kop = KopSurat::all()->first()->kop_isi;
            $pengguna = Pengguna::findOrFail($req->get('edaran_pejabat'));

			$data = new Edaran();
            $data->edaran_nomor = $nomor;
			$data->edaran_tanggal = Carbon::parse($req->get('edaran_tanggal'))->format('Y-m-d');
			$data->edaran_sifat = $req->get('edaran_sifat');
			$data->edaran_perihal = $req->get('edaran_perihal');
			$data->edaran_lampiran = $req->get('edaran_lampiran');
			$data->edaran_kepada = $req->get('edaran_kepada');
			$data->edaran_isi = $req->get('edaran_isi');
			$data->edaran_ttd = $req->get('edaran_jenis_ttd') == 2? $pengguna->gambar->gambar_lokasi: 1;
            $data->edaran_tembusan = $req->get('edaran_tembusan');
            $data->edaran_pejabat = "<strong>".$pengguna->pengguna_nama."</strong><br>".$pengguna->pengguna_pangkat."<br>NIP. ".$pengguna->pengguna_nip;
            $data->jabatan_nama = $pengguna->jabatan_nama;
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
        return view('pages.suratkeluar.edaran.form', [
            'aksi' => 'Edit',
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
                'edaran_tanggal' => 'required',
                'edaran_perihal' => 'required',
                'edaran_isi' => 'required',
                'edaran_kepada' => 'required',
                'edaran_jenis_ttd' => 'required',
                'edaran_pejabat' => 'required'
            ],[
                'edaran_nomor.required'  => 'Nomor tidak boleh kosong',
                'edaran_tanggal.required'  => 'Tanggal Surat tidak boleh kosong',
                'edaran_perihal.required'  => 'Perihal tidak boleh kosong',
                'edaran_isi.required'  => 'Isi tidak boleh kosong',
                'edaran_kepada.required'  => 'Tujuan tidak boleh kosong',
                'edaran_jenis_ttd.required'  => 'Jenis Tanda Tangan tidak boleh kosong',
                'edaran_pejabat.required'  => 'Tanda Tangan tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
            $salam = Salam::all()->first();
            $kop = KopSurat::all()->first()->kop_isi;
            $pengguna = Pengguna::findOrFail($req->get('edaran_pejabat'));

			$data = Edaran::findOrFail($req->get('edaran_nomor'));
			$data->edaran_tanggal = Carbon::parse($req->get('edaran_tanggal'))->format('Y-m-d');
			$data->edaran_sifat = $req->get('edaran_sifat');
			$data->edaran_perihal = $req->get('edaran_perihal');
			$data->edaran_lampiran = $req->get('edaran_lampiran');
			$data->edaran_kepada = $req->get('edaran_kepada');
			$data->edaran_isi = $req->get('edaran_isi');
			$data->edaran_ttd = $req->get('edaran_jenis_ttd') == 2? $pengguna->gambar->gambar_lokasi: 1;
            $data->edaran_tembusan = $req->get('edaran_tembusan');
            $data->edaran_pejabat = "<strong>".$pengguna->pengguna_nama."</strong><br>".$pengguna->pengguna_pangkat."<br>NIP. ".$pengguna->pengguna_nip;
            $data->jabatan_nama = $pengguna->jabatan_nama;
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
                        'edaran_nomor' => $nomor,
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
            return view('layouts.print-surat', [
                'halaman' => 'pages.suratkeluar.edaran.cetak',
                'data' => $data,
                'ttd' => $data->edaran_ttd
                ]);
            $pdf = PDF::loadView('pages.suratkeluar.edaran.cetak', [
                'data' => $data,
            ], [], [
                'format' => 'A4'
            ]);
            return $pdf->stream('EDARAN '.$req->get('no'));
        }catch(\Exception $e){
            $error = $e->getMessage();
        }
    }
}
