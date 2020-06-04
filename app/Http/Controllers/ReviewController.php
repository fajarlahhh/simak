<?php

namespace App\Http\Controllers;

use App\Edaran;
use App\Review;
use App\Pengguna;
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
        $data = Review::where(function($q) use ($req){
            $q->where('review_nomor_surat', 'like', '%'.$req->cari.'%');
        })->orderBy('created_at', 'asc');
        if(Auth::user()->jabatan->jabatan_struktural == 0){
            $data = $data->where('operator', Auth::id())->where('fix', 1)->where('selesai', 0);
        }else{
            $data = $data->where('verifikator', Auth::user()->jabatan_nama)->whereNull('fix');
        }
        $data = $data->paginate(10);

        $data->appends(['cari' => $req->tipe, 'cari' => $req->tipe]);
        return view('pages.review.index', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'tipe' => $req->tipe,
            'cari' => $req->cari
        ]);
    }

	public function review(Request $req)
	{
        switch ($req->get('tipe')) {
            case 'Edaran':
                $data = Edaran::findOrFail($req->no);
                $halaman = 'pages.suratkeluar.edaran.cetak';
                break;
        }
        return view('pages.review.form', [
            'data' => $data,
            'history' => Review::where('review_nomor_surat', $req->no)->where('fix', 1)->orderBy('review_nomor', 'asc')->get(),
            'halaman' => $halaman,
            'back' => Str::contains(url()->previous(), ['review/cek'])? '/review': url()->previous(),
        ]);
	}

	public function do_review(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'review_nomor_surat' => 'required',
                'fix' => 'required'
            ],[
                'review_nomor_surat.required'  => 'Nomor Surat tidak boleh kosong',
                'fix.required'  => 'Hasil tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try
        {
            $data = Review::where('review_nomor', $req->get('review_nomor'))->where('review_nomor_surat', $req->get('review_nomor_surat'))->first();
            DB::transaction(function() use ($req, $data){
                Review::where('review_nomor', $req->get('review_nomor'))->where('review_nomor_surat', $req->get('review_nomor_surat'))->whereNull('fix')
                ->update([
                    'review_catatan' => $req->get('review_catatan'), 
                    'fix' => $req->get('fix'), 
                ]);
                if ($req->get('fix') == 1) {
                    $broadcast = [
                        'pengguna_id' => $data->operator,
                        'surat_nomor' => $req->get('review_nomor_surat'),
                        'surat_jenis' => 'Edaran',
                    ];
                    event(new SuratKeluarEvent($broadcast));
                }else{    
                    $review = new Review();
                    $review->review_nomor_surat = $nomor;
                    $review->review_nomor = 1;
                    $review->review_jenis_surat = "Edaran";
                    $review->verifikator = Auth::user()->jabatan->jabatan_parent;
                    $review->operator = $data->operator;
                    $review->save();

                    $atasan = Pengguna::where('jabatan_nama', Auth::user()->jabatan->jabatan_parent)->get();
    
                    foreach ($atasan as $atasan) {
                        $broadcast = [
                            'pengguna_id' => $atasan->pengguna_id,
                            'surat_nomor' => $nomor,
                            'surat_jenis' => 'Edaran',
                        ];
                        event(new SuratKeluarEvent($broadcast));
                    }
                }
            });

            toast('Berhasil mereview surat '.strtolower($data->review_jenis_surat)." ".$req->get('review_nomor_surat'), 'success')->autoClose(2000);
			return redirect('review');
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}
}
