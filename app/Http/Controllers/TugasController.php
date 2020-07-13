<?php

namespace App\Http\Controllers;

use PDF;
use App\Salam;
use App\Tugas;
use App\Review;
use App\Jabatan;
use App\Opd;
use App\KopSurat;
use App\Pengguna;
use App\Tembusan;
use App\Penomoran;
use Carbon\Carbon;
use App\TugasLampiran;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\SuratKeluarEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class TugasController extends Controller
{
    //

	public function index(Request $req)
	{
        $auth = Auth::user();
        $tahun = $req->tahun? $req->tahun: date('Y');
        $data = Tugas::with('harus_revisi')->where(function($q) use ($req){
            $q->where('tugas_sifat', 'like', '%'.$req->cari.'%')->orWhere('tugas_perihal', 'like', '%'.$req->cari.'%')->orWhere('tugas_nomor', 'like', '%'.$req->cari.'%');
        })->whereYear('tugas_tanggal', '=', $tahun)->orderBy('tugas_tanggal', 'desc');

        if ($auth->getRoleNames()[0] != 'super-admin') {
            $data = $data->where('bidang_id', $auth->jabatan->bidang->bidang_id);
        }

        if ($req->terbit == 1) {
            $data = $data->where('fix', 1);
        }else{
            $data = $data->where('fix', 0);
        }

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

        $data->appends(['cari' => $req->cari, 'tipe' => $req->tipe, 'terbit' => $req->terbit, 'tahun' => $tahun]);
        return view('pages.suratkeluar.tugas.index', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'tipe' => $req->tipe,
            'terbit' => $req->terbit,
            'tahun' => $tahun,
            'cari' => $req->cari
        ]);
    }

    public function detail(Request $req)
    {
        $data = Tugas::with('lampiran')->with('review')->findOrFail($req->no);
        return view('pages.tracking.suratkeluar.form',[
            'data' => $data,
            'halaman' => 'pages.suratkeluar.tugas.cetak',
            'i' => 0
        ]);
    }

	public function tambah()
	{
        return view('pages.suratkeluar.tugas.form', [
            'aksi' => 'Tambah',
            'edit' => 1,
            'data' => null,
            'kepada' => null,
            'tembusan' => null,
            'pengguna' => Pengguna::whereHas('jabatan', function ($q){
                $q->where('jabatan_pimpinan', 1);
            })->get(),
            'back' => Str::contains(url()->previous(), ['tugas/tambah', 'tugas/edit'])? '/tugas': url()->previous(),
        ]);
	}

	public function do_tambah(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'tugas_tanggal' => 'required'
            ],[
                'tugas_tanggal.required'  => 'Tanggal Surat tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
            DB::transaction(function() use ($req){
                $auth = Auth::user();
                $kepada = $req->get('tugas_kepada_awal')."<ol>";
                if($req->get('tugas_kepada_tujuan')){
                    foreach ($req->get('tugas_kepada_tujuan') as $key => $value) {
                        $kepada .= "<li>".$value."</li>";
                    }
                }
                $kepada .= "</ol>".$req->get('tugas_kepada_akhir');

                $format = Penomoran::where('penomoran_jenis', 'tugas')->first()->penomoran_format;
                $urutan = env('EDARAN');
                $data = Tugas::withTrashed()->whereRaw('year(tugas_tanggal)='.date('Y'))->orderBy('urutan', 'desc')->get();
                if($data->count() > 0){
                    $urutan = $data->first()->urutan;
                }
                $cari  = array('$urut$','$bidang$','$tahun$');
                $ganti = array($urutan + 1, $auth->jabatan->bidang->bidang_alias, date('Y'));
                $nomor = str_replace($cari, $ganti, $format);

                $salam = Salam::all()->first();
                $kop = KopSurat::all()->first()->kop_isi;
                if($req->get('tugas_pejabat')){
                    $pengguna = Pengguna::findOrFail($req->get('tugas_pejabat'));
                }

                $tembusan = null;
                if($req->get('tembusan')){
                    $tembusan = Tembusan::all()->first()->tembusan_isi."<ol>";
                    if($req->get('tembusan')){
                        foreach ($req->get('tembusan') as $key => $value) {
                            $opd = Opd::findOrFail($value);
                            $tembusan .= "<li>".$opd->opd_nama." di ".$opd->opd_lokasi."</li>";
                        }
                    }
                    $tembusan .= "</ol>";
                }

                $data = new Tugas();
                $data->tugas_nomor = $nomor;
                $data->tugas_tanggal = Carbon::parse($req->get('tugas_tanggal'))->format('Y-m-d');
                $data->tugas_sifat = $req->get('tugas_sifat');
                $data->tugas_perihal = $req->get('tugas_perihal');
                $data->tugas_lampiran = $req->get('tugas_lampiran');
                $data->tugas_kepada = $kepada;
                $data->tugas_isi = $req->get('tugas_isi');
                if($req->get('tugas_pejabat')){
                    $data->tugas_ttd = $req->get('tugas_jenis_ttd') == 2? ($pengguna->gambar_nama? $pengguna->gambar->gambar_lokasi: null): 1;
                    $data->tugas_pejabat = "<strong>".$pengguna->pengguna_nama."</strong><br>".$pengguna->pengguna_pangkat."<br>NIP. ".$pengguna->pengguna_nip;
                    $data->jabatan_nama = $pengguna->jabatan->jabatan_nama;
                }
                $data->tugas_tembusan = $tembusan;
                $data->salam_pembuka = $salam->salam_pembuka;
                $data->salam_penutup = $salam->salam_penutup;
                $data->kop_isi = $kop;
                $data->urutan = $urutan + 1;
                $data->operator = $auth->pengguna_nama;
                $data->bidang_id = $auth->jabatan->bidang->bidang_id;
                $data->save();

                if($req->hasFile('lampiran'))
                {
                    foreach ($req->file('lampiran') as $file) {
                        $ext = $file->getClientOriginalExtension();
                        $nama_file = time().Str::random().".".$ext;
                        $file->move(public_path('uploads/tugas/gambar'), $nama_file);
                        TugasLampiran::create([
                            'tugas_nomor' => $nomor,
                            'file' => '/uploads/tugas/gambar/'.$nama_file
                            ]);
                    }
                }
                $atasan = Pengguna::where('jabatan_id', $auth->jabatan->jabatan_parent)->get();

                $review = new Review();
                $review->review_surat_nomor = $nomor;
                $review->review_nomor = 1;
                $review->review_surat_jenis = "Tugas";
                $review->jabatan_id = $auth->jabatan->jabatan_parent;
                $review->operator = $auth->pengguna_id;
                $review->save();

                $notif_id = [];
                foreach ($atasan as $atasan) {
                    $broadcast = [
                        'pengguna_id' => $atasan->pengguna_id,
                        'surat_nomor' => $nomor,
                        'surat_jenis' => 'Tugas',
                    ];
                    array_push($notif_id, [
                        $atasan->notif_id
                    ]);
                    event(new SuratKeluarEvent($broadcast));
                }
                if($notif_id){
                    $notif = new PushNotification();
                    $notif->send($notif_id, 'Surat tugas perihal '.$req->get('tugas_perihal'), 'Surat Tugas');
                }
            });
            toast('Berhasil menambah surat tugas '.$req->get('tugas_nomor'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('tugas'));
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function edit(Request $req)
	{
        $data = Tugas::with('lampiran')->findOrFail($req->no);

        $kepada = null;
        if($data && $data->tugas_kepada){
            $kepada = explode("<ol>", $data->tugas_kepada);
        }

        $tembusan = null;
        if($data && $data->tugas_tembusan){
            $tembusan = explode("<ol>", $data->tugas_tembusan);
        }
        $review = Review::where('review_surat_nomor', $req->get('no'))->where('fix', 1)->where('selesai', 0)->first();
        return view('pages.suratkeluar.tugas.form', [
            'aksi' => 'Edit',
            'edit' => 1,
            'catatan' => $review? $review: null,
            'kepada' => $kepada? [$kepada[0], str_replace(array("<li>", "</li>"), array("", ""), explode("</li><li>", explode("</ol>", $kepada[1])[0])), explode("</ol>", $kepada[1])[1]]: null,
            'tembusan' => $tembusan? [str_replace(array("<p>", "</p>"), array("", ""), $tembusan[0]), str_replace(array("<li>", "</li>"), array("", ""), explode("</li><li>", explode("</ol>", $tembusan[1])[0]))]: null,
            'i' => 0,
            'data' => $data,
            'pengguna' => Pengguna::whereHas('jabatan', function ($q) use ($req){
                $q->where('jabatan_pimpinan', 1);
            })->get(),
            'back' => Str::contains(url()->previous(), ['tugas/tambah', 'tugas/edit'])? '/tugas': url()->previous(),
        ]);
	}

	public function edit_isi(Request $req)
	{
        $review = Review::where('review_surat_nomor', $req->get('no'))->where('fix', 1)->where('selesai', 0)->first();
        return view('pages.suratkeluar.tugas.form', [
            'aksi' => 'Edit',
            'edit' => 2,
            'kepada' => null,
            'tembusan' => null,
            'catatan' => $review? $review: null,
            'data' => Tugas::with('lampiran')->findOrFail($req->no),
            'pengguna' => Pengguna::whereHas('jabatan', function ($q) use ($req){
                $q->where('jabatan_struktural', 1);
            })->get(),
            'back' => Str::contains(url()->previous(), ['tugas/tambah', 'tugas/edit'])? '/tugas': url()->previous(),
        ]);
	}

	public function do_edit(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'tugas_nomor' => 'required',
            ],[
                'tugas_nomor.required'  => 'Nomor tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
            DB::transaction(function() use ($req){
                $auth = Auth::user();
                $salam = Salam::all()->first();
                $kop = KopSurat::all()->first()->kop_isi;
                if($req->get('tugas_pejabat')){
                    $pengguna = Pengguna::findOrFail($req->get('tugas_pejabat'));
                }

                $kepada = null;
                if($req->get('tujuan')){
                    $kepada = $req->get('tugas_kepada_awal')."<ol>";
                    if($req->get('tujuan')){
                        foreach ($req->get('tujuan') as $key => $value) {
                            $kepada .= "<li>".$value."</li>";
                        }
                    }
                    $kepada .= "</ol>".$req->get('tugas_kepada_akhir');
                }

                $tembusan = null;
                if($req->get('tembusan')){
                    $tembusan = Tembusan::all()->first()->tembusan_isi."<ol>";
                    if($req->get('tembusan')){
                        foreach ($req->get('tembusan') as $key => $value) {
                            $opd = Opd::findOrFail($value);
                            $tembusan .= "<li>".$opd->opd_nama." di ".$opd->opd_lokasi."</li>";
                        }
                    }
                    $tembusan .= "</ol>";
                }

                $data = Tugas::findOrFail($req->get('tugas_nomor'));
                $data->tugas_tanggal = Carbon::parse($req->get('tugas_tanggal'))->format('Y-m-d');
                $data->tugas_sifat = $req->get('tugas_sifat');
                $data->tugas_perihal = $req->get('tugas_perihal');
                $data->tugas_lampiran = $req->get('tugas_lampiran');
                $data->tugas_kepada = $kepada;
                $data->tugas_isi = $req->get('tugas_isi');
                if($req->get('tugas_pejabat')){
                    $data->tugas_ttd = $req->get('tugas_jenis_ttd') == 2? ($pengguna->gambar_nama? $pengguna->gambar->gambar_lokasi: null): 1;
                    $data->tugas_pejabat = "<strong>".$pengguna->pengguna_nama."</strong><br>".$pengguna->pengguna_pangkat."<br>NIP. ".$pengguna->pengguna_nip;
                    $data->jabatan_nama = $pengguna->jabatan->jabatan_nama;
                }
                $data->tugas_tembusan = $tembusan;
                $data->salam_pembuka = $salam->salam_pembuka;
                $data->salam_penutup = $salam->salam_penutup;
                $data->kop_isi = $kop;
                $data->bidang_id = $auth->jabatan->bidang->bidang_id;
                $data->operator = $auth->pengguna_nama;
                $data->save();

                if($req->hasFile('lampiran'))
                {
                    foreach ($req->file('lampiran') as $file) {
                        $ext = $file->getClientOriginalExtension();
                        $nama_file = time().Str::random().".".$ext;
                        $file->move(public_path('uploads/tugas/gambar'), $nama_file);
                        TugasLampiran::create([
                            'tugas_nomor' => $req->get('tugas_nomor'),
                            'file' => '/uploads/tugas/gambar/'.$nama_file
                            ]);
                    }
                }
                $belum_selesai_review = Review::where('review_surat_nomor', $req->get('tugas_nomor'))->where('fix', 1)->where('selesai', 0)->first();
                if ($belum_selesai_review) {
                    Review::where('review_surat_nomor', $req->get('tugas_nomor'))->where('selesai', 0)->where('fix', 1)
                    ->update([
                        'selesai' => 1,
                    ]);
                    $tujuan = $belum_selesai_review->jabatan_id;
                    if(Jabatan::where('jabatan_id', $belum_selesai_review->jabatan_id)->first()->jabatan_pimpinan == 1){
                        $tujuan = Jabatan::where('jabatan_verifikator', 1)->first()->jabatan_id;
                    }

                    $review = new Review();
                    $review->review_surat_nomor = $req->get('tugas_nomor');
                    $review->review_nomor = $belum_selesai_review->review_nomor + 1;
                    $review->review_surat_jenis = "Tugas";
                    $review->jabatan_id = $tujuan;
                    $review->operator = $auth->pengguna_id;
                    $review->save();

                    $atasan = Pengguna::where('jabatan_id', $tujuan)->get();
                    foreach ($atasan as $atasan) {
                        $broadcast = [
                            'pengguna_id' => $atasan->pengguna_id,
                            'surat_nomor' => $req->get('tugas_nomor'),
                            'surat_jenis' => 'Tugas',
                        ];
                        event(new SuratKeluarEvent($broadcast));
                    }
                    # code...
                }
            });

            toast('Berhasil mengedit surat tugas '.$req->get('tugas_nomor'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('tugas'));
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}

	public function hapus(Request $req)
	{
		try{
            Tugas::findOrFail($req->get('no'))->delete();
            toast('Berhasil menghapus data', 'success')->autoClose(2000);
		}catch(\Exception $e){
            alert()->error('Hapus Data', $e->getMessage());
		}
	}

	public function hapus_lampiran(Request $req)
	{
		try{
            DB::transaction(function() use ($req){
                $data = TugasLampiran::findOrFail($req->get('file'));
                $data->delete();
                File::delete(public_path($req->get('file')));
            });
            return 1;
		}catch(\Exception $e){
            return 0;
		}
	}

	public function restore(Request $req)
	{
		try{
            Tugas::withTrashed()->findOrFail($req->get('no'))->restore();
            toast('Berhasil mengembalikan data', 'success')->autoClose(2000);
		}catch(\Exception $e){
            alert()->error('Restore Data', $e->getMessage());
		}
	}

	public function cetak(Request $req)
	{
        $id = $req->get('no');
        try{
            $data = Tugas::withTrashed()->findOrFail($id);
            $pdf = PDF::loadView('layouts.print-surat', [
                'halaman' => 'pages.suratkeluar.tugas.cetak',
                'data' => $data,
                'judul' => 'Nomor Tugas '.$data->tugas_nomor
            ], [], [
                'format' => 'A4'
            ]);
            return $pdf->stream('EDARAN '.$req->get('no'));
        }catch(\Exception $e){
            $error = $e->getMessage();
        }
    }
}
