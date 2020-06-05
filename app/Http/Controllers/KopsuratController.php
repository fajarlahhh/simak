<?php

namespace App\Http\Controllers;

use App\KopSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KopsuratController extends Controller
{
    //
    public function __construct()
	{
		$this->middleware('auth');
    }

    public function index(Request $req)
	{
        $data = KopSurat::get()->first();
        return view('pages.template.kopsurat.form', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'cari' => $req->cari
        ]);
    }
	public function simpan(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'kop_isi' => 'required'
            ],[
                'kop_isi.required'  => 'Isi tidak boleh kosong'
            ]
        );


        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
            DB::transaction(function() use ($req){
                KopSurat::truncate();
                $data = new KopSurat();
                $data->kop_isi = $req->get('kop_isi');
                $data->operator = Auth::user()->pengguna_nama;
                $data->save();
            });

            toast('Berhasil menyimpan kopsurat ', 'success')->autoClose(2000);
			return redirect(route('kopsurat'));
        }catch(\Exception $e){
            alert()->error('Simpan Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}
}
