<?php

namespace App\Http\Controllers;

use App\Jabatan;
use App\Disposisi;
use App\SuratMasuk;
use App\DisposisiDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DisposisiController extends Controller
{
    //
	public function index(Request $req)
	{
        $auth = Auth::user();
        $data = null;
        if($auth->jabatan->jabatan_pimpinan == 1){
            $data = SuratMasuk::where('disposisi', 0)->where(function($q) use ($req){
                $q->where('surat_masuk_asal', 'like', '%'.$req->cari.'%')->orWhere('surat_masuk_perihal', 'like', '%'.$req->cari.'%')->orWhere('surat_masuk_keterangan', 'like', '%'.$req->cari.'%')->orWhere('surat_masuk_nomor', 'like', '%'.$req->cari.'%');
            })->orderBy('surat_masuk_tanggal_masuk', 'desc')->get([
                'surat_masuk_id AS id',
                'surat_masuk_nomor AS nomor',
                'surat_masuk_asal AS asal',
                'surat_masuk_perihal AS perihal',
                'surat_masuk_tanggal_surat AS tanggal_surat',
                'surat_masuk_tanggal_masuk AS tanggal_masuk',
                'created_at AS created_at',
                'updated_at AS updated_at',
                'operator AS operator',
                DB::raw('"Surat Masuk" as jenis')
            ]);
        }else{
            $data = Disposisi::with('surat_masuk')->whereHas('detail', function ($q) use ($auth){
                $q->where('jabatan_id', $auth->jabatan_id);
            })->orderBy('created_at', 'desc')->get([
                'disposisi_surat_id AS id',
                'surat_masuk_nomor AS nomor',
                'surat_masuk_asal AS asal',
                'surat_masuk_perihal AS perihal',
                'surat_masuk_tanggal_surat AS tanggal_surat',
                'surat_masuk_tanggal_masuk AS tanggal_masuk',
                'created_at AS created_at',
                'updated_at AS updated_at',
                'operator AS operator',
                DB::raw('"Surat Masuk" as jenis')
            ]);
        }
        return view('pages.disposisi.index', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'cari' => $req->cari
        ]);
    }

	public function disposisi(Request $req)
	{
        switch ($req->get('tipe')) {
            case 'Surat Masuk':
                $data = SuratMasuk::with('disposisi')->where('surat_masuk_id', $req->id)->get([
                    'surat_masuk_id AS id',
                    'surat_masuk_nomor AS nomor',
                    'surat_masuk_asal AS asal',
                    'surat_masuk_perihal AS perihal',
                    'surat_masuk_tanggal_surat AS tanggal_surat',
                    'surat_masuk_tanggal_masuk AS tanggal_masuk',
                    'file AS file',
                    'created_at AS created_at',
                    'updated_at AS updated_at',
                    'operator AS operator',
                    DB::raw('"Surat Masuk" as jenis')
                ])->first();
                break;
        }
        return view('pages.disposisi.form', [
            'data' => $data,
            'bawahan' => Jabatan::where('jabatan_parent', Auth::user()->jabatan_id)->get(),
            'back' => Str::contains(url()->previous(), ['disposisi/form'])? '/disposisi': url()->previous(),
        ]);
	}

	public function do_disposisi(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'disposisi_surat_id' => 'required',
                'disposisi_sifat' => 'required',
                'disposisi_catatan' => 'required',
                'jabatan_id' => 'required'
            ],[
                'disposisi_surat_id.required'  => 'Surat tidak boleh kosong',
                'disposisi_sifat.required'  => 'Hasil tidak boleh kosong',
                'disposisi_catatan.required'  => 'Catatan tidak boleh kosong',
                'jabatan_id.required'  => 'Tujuan Disposisi harus dipilih'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try
        {
            DB::transaction(function() use ($req){
                if (Auth::user()->jabatan->jabatan_pimpinan == 1) {
                    switch ($req->disposisi_jenis_surat) {
                        case 'Surat Masuk':
                            SuratMasuk::where('surat_masuk_id', $req->disposisi_surat_id)->update([
                                'disposisi' => 1
                            ]);
                            break;
                    }
                }else{
                    SuratMasuk::where('surat_masuk_id', $req->disposisi_surat_id)->update([
                        'disposisi' => 1
                    ]);
                }
                $data = new Disposisi();
                $data->disposisi_surat_id = $req->disposisi_surat_id;
                $data->disposisi_jenis_surat = $req->disposisi_jenis_surat;
                $data->disposisi_sifat = $req->disposisi_sifat;
                $data->disposisi_catatan = $req->disposisi_catatan;
                $data->disposisi_proses = $req->disposisi_proses;
                $data->disposisi_hasil = $req->disposisi_hasil;
                $data->operator = Auth::id();
                $data->save();

                foreach ($req->jabatan_id as $key => $value) {
                    DisposisiDetail::create([
                        'disposisi_id' => $data->disposisi_id,
                        'jabatan_id' => $value
                        ]);
                }
            });

            toast('Disposisi berhasil', 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('disposisi'));
        }catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}
}
