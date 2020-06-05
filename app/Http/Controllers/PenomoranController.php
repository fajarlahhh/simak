<?php

namespace App\Http\Controllers;

use App\Penomoran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
            DB::transaction(function() use ($req){
                Penomoran::truncate();
                Penomoran::insert(
                    [
                        [
                            'penomoran_jenis' => 'edaran',
                            'penomoran_format' => $req->get('edaran'),
                            'operator' => Auth::user()->pengguna_nama,
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ],
                        [
                            'penomoran_jenis' => 'sk',
                            'penomoran_format' => $req->get('sk'),
                            'operator' => Auth::user()->pengguna_nama,
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ],
                        [
                            'penomoran_jenis' => 'pengantar',
                            'penomoran_format' => $req->get('pengantar'),
                            'operator' => Auth::user()->pengguna_nama,
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ],
                        [
                            'penomoran_jenis' => 'tugas',
                            'penomoran_format' => $req->get('tugas'),
                            'operator' => Auth::user()->pengguna_nama,
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ],
                        [
                            'penomoran_jenis' => 'undangan',
                            'penomoran_format' => $req->get('undangan'),
                            'operator' => Auth::user()->pengguna_nama,
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ]
                    ]
                );
            });

            toast('Berhasil menyimpan penomoran ', 'success')->autoClose(2000);
			return redirect(route('penomoran'));
        }catch(\Exception $e){
            alert()->error('Edit Data', $e->getMessage());
            return redirect()->back()->withInput();
        }
	}
}
