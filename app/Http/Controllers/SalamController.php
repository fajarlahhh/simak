<?php

namespace App\Http\Controllers;

use App\Salam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SalamController extends Controller
{
    //
    public function __construct()
	{
		$this->middleware('auth');
    }

    public function index(Request $req)
	{
        $data = Salam::all()->first();
        return view('pages.template.salam.form', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'cari' => $req->cari
        ]);
    }
	public function simpan(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'salam_pembuka' => 'required',
                'salam_penutup' => 'required'
            ],[
                'salam_pembuka.required'  => 'Salam Pembuka tidak boleh kosong',
                'salam_penutup.required'  => 'Salam Penutup tidak boleh kosong'
            ]
        );


        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
            DB::transaction(function() use ($req){
                Salam::truncate();
                $data = new Salam();
                $data->salam_pembuka = $req->get('salam_pembuka');
                $data->salam_penutup = $req->get('salam_penutup');
                $data->operator = Auth::user()->pengguna_nama;
                $data->save();
            });

            toast('Berhasil menyimpan salam ', 'success')->autoClose(2000);
			return redirect(route('salam'));
        }catch(\Exception $e){
            alert()->error('Simpan Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}
}
