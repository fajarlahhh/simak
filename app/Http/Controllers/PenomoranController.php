<?php

namespace App\Http\Controllers;

use App\Penomoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenomoranController extends Controller
{
    //
    public function __construct()
	{
		$this->middleware('auth');
    }

    public function index(Request $req)
	{
        $data = Penomoran::get();
        return view('pages.template.penomoran.form', [
            'data' => collect($data),
            'i' => ($req->input('page', 1) - 1) * 10,
            'cari' => $req->cari
        ]);
    }
	public function simpan(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'edaran' => 'required',
                'sk' => 'required',
                'pengantar' => 'required',
                'tugas' => 'required',
                'undangan' => 'required'
            ],[
                'edaran.required'  => 'Edaran tidak boleh kosong',
                'sk.required'  => 'SK tidak boleh kosong',
                'pengantar.required'  => 'Surat Pengantar tidak boleh kosong',
                'tugas.required'  => 'Surat Tugas tidak boleh kosong',
                'undangan.required'  => 'Undangan tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

        try{
            Penomoran::truncate();

            $data = new Penomoran();
			$data->penomoran_jenis = 'edaran';
			$data->penomoran_format = $req->get('edaran');
            $data->save();

            $data = new Penomoran();
			$data->penomoran_jenis = 'sk';
			$data->penomoran_format = $req->get('sk');
            $data->save();

            $data = new Penomoran();
			$data->penomoran_jenis = 'pengantar';
			$data->penomoran_format = $req->get('pengantar');
            $data->save();

            $data = new Penomoran();
			$data->penomoran_jenis = 'tugas';
			$data->penomoran_format = $req->get('tugas');
            $data->save();

            $data = new Penomoran();
			$data->penomoran_jenis = 'undangan';
			$data->penomoran_format = $req->get('undangan');
            $data->save();

            toast('Berhasil menyimpan penomoran ', 'success')->autoClose(2000);
			return redirect(route('penomoran'));
        }catch(\Exception $e){
            alert()->error('Edit Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}
}
