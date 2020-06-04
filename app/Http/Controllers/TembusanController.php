<?php

namespace App\Http\Controllers;

use App\Tembusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TembusanController extends Controller
{
    //
    public function __construct()
	{
		$this->middleware('auth');
    }

    public function index(Request $req)
	{
        $data = Tembusan::get()->first();
        return view('pages.template.tembusan.form', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'cari' => $req->cari
        ]);
    }
	public function simpan(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'tembusan_isi' => 'required'
            ],[
                'tembusan_isi.required'  => 'Tembusan tidak boleh kosong'
            ]
        );


        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
            $data = new Tembusan();
            $data->truncate();
			$data->tembusan_isi = $req->get('tembusan_isi');
			$data->operator = Auth::user()->pengguna_nama;
            $data->save();

            toast('Berhasil menyimpan tembusan ', 'success')->autoClose(2000);
			return redirect(route('tembusan'));
        }catch(\Exception $e){
            alert()->error('Simpan Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}
}
