<?php

namespace App\Http\Controllers;

use App\Jabatan;
use App\Pengguna;
use App\Disposisi;
use App\SuratMasuk;
use App\DisposisiDetail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\SuratMasukEvent;
use Illuminate\Support\Facades\DB;
use App\OneSignal\PushNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DisposisiController extends Controller
{
    //
	public function index(Request $req)
	{
        $auth = Auth::user();
        $data = $this->data($auth, $req->cari);
        return view('pages.disposisi.index', [
            'data' => collect($data),
            'i' => ($req->input('page', 1) - 1) * 10,
            'cari' => $req->cari
        ]);
    }

	public function get($pengguna, Request $req)
	{
        $auth = Pengguna::findOrFail($pengguna);
        return response()->json($this->data($auth, $req->cari));
    }

	public function data($auth, $cari = null)
	{
        $disposisi = [];
        if($auth->jabatan->jabatan_pimpinan == 1){
            $disposisi = SuratMasuk::where('disposisi', 0)->get([
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
            ])->toArray();
        }else{
            $data = Disposisi::with('surat_masuk')->whereHas('detail', function ($q) use ($auth){
                $q->where('jabatan_id', $auth->jabatan_id)->where('proses', 0);
            })->orderBy('created_at', 'desc')->get();
            foreach ($data as $row) {
                array_push($disposisi, [
                    'id' => $row->disposisi_id,
                    'nomor' => $row->surat_masuk->surat_masuk_nomor,
                    'asal' => $row->surat_masuk->surat_masuk_asal,
                    'perihal' => $row->surat_masuk->surat_masuk_perihal,
                    'tanggal_surat' => $row->surat_masuk->surat_masuk_tanggal_surat,
                    'tanggal_masuk' => $row->surat_masuk->surat_masuk_tanggal_masuk,
                    'created_at' => $row->surat_masuk->created_at,
                    'updated_at' => $row->surat_masuk->updated_at,
                    'operator' => $row->operator,
                    'jenis' => "Surat Masuk"
                ]);
            }
        }
        return $disposisi;
    }

	public function disposisi(Request $req)
	{
        $auth = Auth::user();
        $disposisi = null;

        if($auth->jabatan->jabatan_pimpinan == 1){
            switch ($req->get('tipe')) {
                case 'Surat Masuk':
                    $disposisi = SuratMasuk::with('disposisi')->where('surat_masuk_id', $req->id)->where('disposisi', 0)->get([
                        DB::raw('null as id'),
                        'surat_masuk_id AS surat',
                        'surat_masuk_nomor AS nomor',
                        'surat_masuk_asal AS asal',
                        'surat_masuk_perihal AS perihal',
                        'surat_masuk_tanggal_surat AS tanggal_surat',
                        'surat_masuk_tanggal_masuk AS tanggal_masuk',
                        'file AS file',
                        'created_at AS created_at',
                        'updated_at AS updated_at',
                        'operator AS operator',
                        DB::raw('"Surat Masuk" as jenis'),
                        DB::raw('null as atasan'),
                        DB::raw('null as sifat'),
                        DB::raw('null as catatan'),
                        DB::raw('null as hasil')
                    ])->first();
                    break;
            }
            if($disposisi == null){
                alert()->error('Disposisi', "Data tidak ditemukan");
                return redirect()->back()->withInput();
            }
        }else{
            if(DisposisiDetail::where('disposisi_id', $req->id)->where('jabatan_id', $auth->jabatan_id)->where('proses', 0)->count() == 0){
                alert()->error('Disposisi', "Data tidak ditemukan");
                return redirect()->back()->withInput();
            }
            switch ($req->get('tipe')) {
                case 'Surat Masuk':
                    $disposisi = Disposisi::with('surat_masuk')->where('disposisi_id', $req->id)->get()->map(function ($q) {
                        return [
                            'id'  => $q->disposisi_id,
                            'surat'  => $q->disposisi_surat_id,
                            'nomor' => $q->surat_masuk->surat_masuk_nomor,
                            'asal' => $q->surat_masuk->surat_masuk_asal,
                            'perihal' => $q->surat_masuk->surat_masuk_perihal,
                            'tanggal_surat' => $q->surat_masuk->surat_masuk_tanggal_surat,
                            'tanggal_masuk' => $q->surat_masuk->surat_masuk_tanggal_masuk,
                            'file' => $q->surat_masuk->file,
                            'created_at' => $q->surat_masuk->created_at,
                            'updated_at' => $q->surat_masuk->updated_at,
                            'operator' => $q->surat_masuk->operator,
                            'jenis' => "Surat Masuk",
                            'atasan'  => $q->jabatan->jabatan_nama,
                            'sifat'  => $q->disposisi_sifat,
                            'catatan'  => $q->disposisi_sifat,
                            'hasil'  => $q->disposisi_hasil,
                        ];
                    })->first();
                    break;
            }
        }
        return view('pages.disposisi.form', [
            'data' => $disposisi,
            'bawahan' => Jabatan::where('jabatan_parent', Auth::user()->jabatan_id)->where('jabatan_struktural', 1)->get(),
            'back' => Str::contains(url()->previous(), ['disposisi/form'])? '/disposisi': url()->previous(),
        ]);
	}

    public function selesai(Request $req)
    {
        $validator = Validator::make($req->all(),
            [
                'disposisi_catatan' => 'required'
            ],[
                'disposisi_catatan.required'  => 'Tanggapan tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try
        {
            DB::transaction(function() use ($req){
                $auth = Auth::user();
                DisposisiDetail::where('disposisi_id', $req->disposisi_id)->where('jabatan_id', $auth->jabatan_id)->update([
                    'proses' => 1
                    ]);

                $data = new Disposisi();
                $data->disposisi_surat_id = $req->disposisi_surat_id;
                $data->disposisi_jenis_surat = $req->disposisi_jenis_surat;
                $data->disposisi_sifat = "11111111";
                $data->disposisi_catatan = $req->disposisi_catatan;
                $data->disposisi_proses = "11111111";
                $data->disposisi_hasil = "11111111";
                $data->jabatan_id = $auth->jabatan_id;
                $data->operator = Auth::id();
                $data->save();
            });

            toast('Disposisi berhasil', 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('disposisi'));
        }catch(\Exception $e){
            alert()->error('Disposisi', $e->getMessage());
            return redirect()->back()->withInput();
        }
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
                $auth = Auth::user();
                if ($auth->jabatan->jabatan_pimpinan == 1) {
                    switch ($req->disposisi_jenis_surat) {
                        case 'Surat Masuk':
                            SuratMasuk::where('surat_masuk_id', $req->disposisi_surat_id)->update([
                                'disposisi' => 1
                            ]);
                            break;
                    }
                }else{
                    DisposisiDetail::where('disposisi_id', $req->disposisi_id)->where('jabatan_id', $auth->jabatan_id)->update([
                        'proses' => 1
                        ]);
                }

                $data = new Disposisi();
                $data->disposisi_surat_id = $req->disposisi_surat_id;
                $data->disposisi_jenis_surat = $req->disposisi_jenis_surat;
                $data->disposisi_sifat = $req->disposisi_sifat;
                $data->disposisi_catatan = $req->disposisi_catatan;
                $data->disposisi_proses = $req->disposisi_proses;
                $data->disposisi_hasil = $req->disposisi_hasil;
                $data->jabatan_id = $auth->jabatan_id;
                $data->operator = Auth::id();
                $data->save();

                $notif_id = [];
                foreach ($req->jabatan_id as $key => $value) {
                    DisposisiDetail::create([
                        'disposisi_id' => $data->disposisi_id,
                        'jabatan_id' => $value
                        ]);
                    $pengguna = Pengguna::where('jabatan_id', $value)->get();
                    foreach ($pengguna as $row) {
                        $broadcast = [
                            'pengguna_id' => $row->pengguna_id,
                            'surat_nomor' => $data->surat_masuk->surat_masuk_nomor,
                            'surat_jenis' => 'Masuk',
                        ];
                        array_push($notif_id,
                            $row->notif_id
                        );
                        event(new SuratMasukEvent($broadcast));
                    }
                }
                if($notif_id){
                    $notif = new PushNotification($notif_id, 'Surat masuk dari '.$data->surat_masuk->surat_masuk_asal, 'Surat Masuk');
                    $notif->send();
                }
            });

            toast('Disposisi berhasil', 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): route('disposisi'));
        }catch(\Exception $e){
            alert()->error('Disposisi', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}
}
