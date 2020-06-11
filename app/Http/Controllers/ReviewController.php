<?php

namespace App\Http\Controllers;

use App\Tugas;
use App\Edaran;
use App\Review;
use App\Jabatan;
use App\Pengguna;
use App\Undangan;
use App\Pengantar;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\SuratKeluarEvent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    //
	public function index(Request $req)
	{
        $auth = Auth::user();
        $data = $this->data(0, $auth, $req->cari);
        $data->appends(['cari' => $req->tipe]);
        return view('pages.review.index', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'cari' => $req->cari
        ]);
    }

	public function get($pengguna, Request $req)
	{
        $auth = Pengguna::findOrFail($pengguna);
        return response()->json($this->data(1, $auth, $req->cari));
    }

	public function data($api = 1, $auth, $cari = null)
	{
        $data = Review::with('edaran')->where(function($q) use ($cari){
            $q->where('review_surat_nomor', 'like', '%'.$cari.'%');
        })->orderBy('created_at', 'asc');
        if($auth->jabatan->jabatan_struktural == 0){
            $data = $data->where('operator', $auth->jabatan_id)->where('fix', 1)->where('selesai', 0);
        }else{
            $data = $data->where('jabatan_id', $auth->jabatan_id)->whereNull('fix');
        }

        if ($api == 1) {
            return $data->get();
        }else{
            return $data->paginate(10);
        }
    }

	public function review(Request $req)
	{
        switch ($req->get('tipe')) {
            case 'Edaran':
                $data = Edaran::findOrFail($req->no);
                $halaman = 'pages.suratkeluar.edaran.cetak';
                break;
            case 'Pengantar':
                $data = Pengantar::findOrFail($req->no);
                $halaman = 'pages.suratkeluar.pengantar.cetak';
                break;
            case 'Tugas':
                $data = Tugas::findOrFail($req->no);
                $halaman = 'pages.suratkeluar.tugas.cetak';
                break;
            case 'Undangan':
                $data = Undangan::findOrFail($req->no);
                $halaman = 'pages.suratkeluar.undangan.cetak';
                break;
        }
        return view('pages.review.form', [
            'data' => $data,
            'atasan' => Auth::user()->jabatan->atasan,
            'history' => Review::where('review_surat_nomor', $req->no)->where('fix', 1)->orderBy('review_nomor', 'desc')->get(),
            'halaman' => $halaman,
            'back' => Str::contains(url()->previous(), ['review/cek'])? '/review': url()->previous(),
        ]);
	}

	public function do_review(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'review_surat_nomor' => 'required',
                'fix' => 'required'
            ],[
                'review_surat_nomor.required'  => 'Nomor Surat tidak boleh kosong',
                'fix.required'  => 'Hasil tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try
        {
            $data = Review::where('review_nomor', $req->get('review_nomor'))->where('review_surat_nomor', $req->get('review_surat_nomor'))->first();
            $pesan = null;
            DB::transaction(function() use ($req, $data){
                $review = Review::where('review_nomor', $req->get('review_nomor'))->where('review_surat_nomor', $req->get('review_surat_nomor'))->whereNull('fix');
                switch ($req->get('fix')) {
                    case 1:
                        $pesan = "mereview";
                        $review->update([
                            'review_catatan' => $req->get('review_catatan'),
                            'fix' => $req->get('fix'),
                        ]);
                        $broadcast = [
                            'pengguna_id' => $data->operator,
                            'surat_nomor' => $req->get('review_surat_nomor'),
                            'surat_jenis' => $data->review_surat_jenis,
                        ];
                        event(new SuratKeluarEvent($broadcast));
                        break;
                    case 2:
                        $pesan = "meneruskan ke atasan";
                        $atasan = Auth::user()->jabatan->atasan;
                        $review->update([
                            'jabatan_id' => $atasan->jabatan_id
                        ]);
                        $broadcast = [
                            'pengguna_id' => $atasan->jabatan_id,
                            'surat_nomor' => $req->get('review_surat_nomor'),
                            'surat_jenis' => $data->review_surat_jenis,
                        ];
                        event(new SuratKeluarEvent($broadcast));
                        break;
                    case 3:
                        $pesan = "meneruskan ke verifikator";
                        $atasan = Jabatan::where('jabatan_verifikator', 1)->first();
                        $review->update([
                            'jabatan_id' => $atasan->jabatan_id
                        ]);
                        $broadcast = [
                            'pengguna_id' => $atasan->jabatan_id,
                            'surat_nomor' => $req->get('review_surat_nomor'),
                            'surat_jenis' => $data->review_surat_jenis,
                        ];
                        event(new SuratKeluarEvent($broadcast));
                        break;
                    case 4:
                        $pesan = "meneruskan ke pimpinan";
                        $atasan = Jabatan::where('jabatan_pimpinan', 1)->first();
                        $review->update([
                            'jabatan_id' => $atasan->jabatan_id
                        ]);
                        $broadcast = [
                            'pengguna_id' => $atasan->jabatan_id,
                            'surat_nomor' => $req->get('review_surat_nomor'),
                            'surat_jenis' => $data->review_surat_jenis,
                        ];
                        event(new SuratKeluarEvent($broadcast));
                        break;
                    case 5:
                        $pesan = "menyetujui & menerbitkan";
                        $atasan = Jabatan::where('jabatan_pimpinan', 1)->first();
                        $review->update([
                            'selesai' => 1,
                            'fix' => $req->get('fix'),
                        ]);
                        switch ($data->review_surat_jenis) {
                            case 'Edaran':
                                Edaran::where('edaran_nomor', $data->review_surat_nomor)->update([
                                    'fix' => 1
                                ]);
                                break;
                            case 'Pengantar':
                                Pengantar::where('pengantar_nomor', $data->review_surat_nomor)->update([
                                    'fix' => 1
                                ]);
                                break;
                            case 'Tugas':
                                Tugas::where('tugas_nomor', $data->review_surat_nomor)->update([
                                    'fix' => 1
                                ]);
                                break;
                            case 'Undangan':
                                Undangan::where('undangan_nomor', $data->review_surat_nomor)->update([
                                    'fix' => 1
                                ]);
                                break;
                        }
                        $broadcast = [
                            'pengguna_id' => $atasan->jabatan_id,
                            'surat_nomor' => $req->get('review_surat_nomor'),
                            'surat_jenis' => $data->review_surat_jenis,
                        ];
                        event(new SuratKeluarEvent($broadcast));
                        break;
                }
            });

            toast('Berhasil  surat '.strtolower($data->review_surat_jenis)." ".$req->get('review_surat_nomor'), 'success')->autoClose(2000);
			return redirect('review');
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}
}
