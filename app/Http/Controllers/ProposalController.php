<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProposalController extends Controller
{
    //
    public function tambah(Request $req)
    {
		return view('pages.pengelolaanproposal.inputproposal.form')
        ->with('back', 'inputproposal')
        ->with('aksi', 'Tambah');
    }

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
                'proposal_nomor' => 'required',
                'proposal_tanggal' => 'required',
                'proposal_asal' => 'required',
                'proposal_tanggal_terima' => 'required',
                'proposal_penerima' => 'required',
                'proposal_perihal' => 'required',
                'proposal_uraian' => 'required',
                'proposal_nilai'  => 'required',
                'proposal_tanggal_pelaksanaan'  => 'required',
                'proposal_file'  => 'required|file|mimes:pdf',
			],[
                'proposal_nomor.required' => 'Nomor proposal tidak boleh kosong',
                'proposal_tanggal.required'  => 'Tanggal proposal tidak boleh kosong',
                'proposal_asal.required'  => 'Asal proposal tidak boleh kosong',
                'proposal_tanggal_terima.required'  => 'Tanggal terima tidak boleh kosong',
                'proposal_penerima.required'  => 'Penerima tidak boleh kosong',
                'proposal_perihal.required'  => 'Perihal tidak boleh kosong',
                'proposal_uraian.required'  => 'Uraian tidak boleh kosong',
                'proposal_nilai.required'  => 'Nilai proposal tidak boleh kosong',
                'proposal_tanggal_pelaksanaan.required'  => 'Tanggal pelaksanaan tidak boleh kosong',
                'proposal_file.required'  => 'File PDF tidak boleh kosong',
                'proposal_file.mimes'  => 'File yang diupload harus PDF',
        	]
		);
		try{
            $pdf = $request->file('proposal_file');
            $pdf_nama = time().$uploadedFile->getClientOriginalName();
            Storage::disk('public')->putFileAs(
                'uploads/pdf/proposal/'.$pdf_nama,
                $pdf,
                $pdf_nama
              );
			$proposal = new SuratMasuk();
			$proposal->proposal_nomor = $req->get('proposal_nomor');
			$proposal->proposal_tanggal = $req->get('proposal_tanggal');
			$proposal->proposal_asal = $req->get('proposal_asal');
			$proposal->proposal_tanggal_terima = $req->get('proposal_tanggal_terima');
			$proposal->proposal_penerima = $req->get('proposal_penerima');
			$proposal->proposal_perihal = $req->get('proposal_perihal');
			$proposal->proposal_uraian = $req->get('proposal_uraian');
			$proposal->proposal_nilai = str_replace(',', '', $req->get('proposal_nilai'));
			$proposal->proposal_tanggal_pelaksanaan = $req->get('proposal_tanggal_pelaksanaan');
			$proposal->proposal_catatan = $req->get('proposal_catatan');
            $proposal->proposal_file = 'uploads/pdf/proposal/'.$pdf_nama;
            if ($req->get('penyimpanan_id')) {
                $proposal->penyimpanan_id = $req->get('penyimpanan_id');
                $proposal->penyimpanan_operator = ucfirst(strtolower(explode(', ', Redis::get(Session::getId()))[0]));
                $proposal->penyimpanan_waktu = Carbon::now();
            }
			$proposal->created_operator = ucfirst(strtolower(explode(', ', Redis::get(Session::getId()))[0]));
            $proposal->save();

			return redirect($req->get('redirect')? $req->get('redirect'): 'proposal')
			->with('swal_pesan', 'Berhasil menambah data proposal '.$req->get('proposal_nomor'))
			->with('swal_judul', 'Tambah data')
			->with('swal_tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'proposal')
			->with('swal_pesan', $e->getMessage())
			->with('swal_judul', 'Tambah data')
			->with('swal_tipe', 'error');
		}
	}
}
