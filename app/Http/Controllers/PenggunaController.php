<?php

namespace App\Http\Controllers;

use App\Pegawai;
use App\Pengguna;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PenggunaController extends Controller
{
    //
	public function __construct()
	{
        $this->middleware('auth');
	}

	public function index(Request $req)
	{
		$pengguna = Pengguna::with(['pegawai' => function($q) use ($req){
			$q->select('nip','kd_unit','kd_jabatan','kd_bagian','kd_seksi', 'nm_pegawai')->where('nm_pegawai', 'like', '%'.$req->cari.'%');
		}])->paginate(10);

		$pengguna->appends($req->only('cari'));
		return view('pages.setup.pengguna.index', [
            'data' => $pengguna,
            'i' => ($req->input('page', 1) - 1) * 10,
            'cari' => $req->cari,
        ]);
	}

	public function tambah()
	{
        try{
            return view('pages.setup.pengguna.form', [
                'pegawai' => Pegawai::select('nip', 'nm_pegawai')->orderBy('nm_pegawai', 'asc')->whereNotIn('nip', Pengguna::select('pengguna_id')->get())->where('kd_status', '!=', '07')->get(),
                'level' => \Spatie\Permission\Models\Role::all(),
                'back' => Str::contains(url()->previous(), ['datapengguna/tambah', 'datapengguna/edit'])? '/datapengguna': url()->previous(),
                'aksi' => 'Tambah'
            ]);
		}catch(\Exception $e){
			return redirect(url()->previous()? url()->previous(): 'datapengguna')
			->with('swal_pesan', $e->getMessage())
			->with('swal_judul', 'Tambah Data')
			->with('swal_tipe', 'error');
		}
	}

	public function ganti_sandi($value='')
	{
		return view('includes.component.modal-password');
	}

	public function do_tambah(Request $req)
	{
		$req->validate(
			[
				'pengguna_id' => 'required',
				'pengguna_sandi' => 'required|min:8',
				'pengguna_hp' => 'required|min:10',
				'pengguna_level' => 'required'
			],[
         	   'pengguna_id.required' => 'Pegawai tidak boleh kosong',
         	   'pengguna_sandi.min' => 'Kata Sandi minimal 8 karakter',
         	   'pengguna_sandi.required'  => 'Kata Sandi tidak boleh kosong',
         	   'pengguna_hp.min' => 'No. Hp minimal 10 karakter',
         	   'pengguna_hp.required'  => 'No. Hp tidak boleh kosong',
         	   'pengguna_level.required'  => 'Level tidak boleh kosong'
        	]
		);
		try{
			$foto = $this->foto($req->get('pengguna_id'));
			$pengguna = new Pengguna();
			$pengguna->pengguna_id = $req->get('pengguna_id');
			$pengguna->pengguna_hp = $req->get('pengguna_hp');
			$pengguna->pengguna_sandi = Hash::make($req->get('pengguna_sandi'));
			$pengguna->pengguna_foto = $foto;
			$pengguna->created_operator = ucfirst(strtolower(explode(', ', Redis::get(Session::getId()))[0]));
			$pengguna->save();
			$pengguna->assignRole($req->get('pengguna_level'));

			$pengguna->givePermissionTo('dashboard');
			if($req->get('izin')){
				for ($i=0; $i < sizeof($req->get('izin')); $i++) {
					$pengguna->givePermissionTo($req->get('izin')[$i]);
				}
			}
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapengguna')
			->with('swal_pesan', 'Berhasil menambah data pengguna ID: '.$req->get('pengguna_id'))
			->with('swal_judul', 'Tambah data')
			->with('swal_tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapengguna')
			->with('swal_pesan', $e->getMessage())
			->with('swal_judul', 'Tambah data')
			->with('swal_tipe', 'error');
		}
	}

	public function edit($id)
	{
		try{
			return view('pages.setup.pengguna.form', [
                'data' => Pengguna::findOrFail($id),
                'level' => (in_array($id, config('admin.nip'))? Role::where('name', 'Admin')->get(): Role::all()),
                'back' => Str::contains(url()->previous(), 'datapengguna/edit')? '/datapengguna': url()->previous(),
                'aksi' => 'Edit',
            ]);
		}catch(\Exception $e){
			return redirect(url()->previous()? url()->previous(): 'datapengguna')
			->with('swal_pesan', $e->getMessage())
			->with('swal_judul', 'Edit Data')
			->with('swal_tipe', 'error');
		}
	}

	public function do_edit(Request $req)
	{
		$req->validate(
			[
				'pengguna_id' => 'required',
				'pengguna_nama' => 'required',
				'pengguna_hp' => 'required|min:10',
				'pengguna_level' => 'required'
			],[
         	   'pengguna_id.required' => 'NIP tidak boleh kosong',
         	   'pengguna_nama.required'  => 'Nama Pegawai tidak boleh kosong',
         	   'pengguna_hp.min' => 'No. Hp minimal 10 karakter',
         	   'pengguna_hp.required'  => 'No. Hp tidak boleh kosong',
         	   'pengguna_level.required'  => 'Level tidak boleh kosong'
        	]
		);
		try{
			$foto = $this->foto($req->get('pengguna_id'));
			DB::table('model_has_permissions')->where('pengguna_id', $req->get('pengguna_id'))->delete();
			$pengguna = new Pengguna();
			$pengguna->exists = true;
			$pengguna->pengguna_id = $req->get('pengguna_id');
			if ($req->get('pengguna_sandi')) {
				$pengguna->pengguna_sandi = Hash::make($req->get('pengguna_sandi'));
			}
			$pengguna->pengguna_hp = $req->get('pengguna_hp');
			$pengguna->pengguna_foto = $foto;
			$pengguna->updated_operator = ucfirst(strtolower(explode(', ', Redis::get(Session::getId()))[0]));
			$pengguna->save();
			$pengguna->removeRole($pengguna->getRoleNames()[0]);
			$pengguna->assignRole($req->get('pengguna_level'));
			if($req->get('izin')){
				foreach ($req->get('izin') as $key => $izin) {
					$pengguna->givePermissionTo($izin);
				}
			}
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapengguna')
			->with('swal_pesan', 'Berhasil mengedit data pengguna ID: '.$req->get('pengguna_id'))
			->with('swal_judul', 'Edit data')
			->with('swal_tipe', 'success');
		}catch(\Exception $e){
			return redirect($req->get('redirect')? $req->get('redirect'): 'datapengguna')
			->with('swal_pesan', $e->getMessage())
			->with('swal_judul', 'Edit data')
			->with('swal_tipe', 'error');
		}
	}


	public function do_ganti_sandi(Request $req)
	{
		$req->validate(
			[
				'pengguna_sandi_baru' => 'required',
				'pengguna_sandi_lama' => 'required',
			],[
         	   'pengguna_sandi_lama.required' => 'Sandi Lama tidak boleh kosong',
         	   'pengguna_sandi_baru.required'  => 'Sandi Baru tidak boleh kosong',
        	]
		);
		try{
			$pengguna = Pengguna::findOrFail(Auth::user()->pegawai->nip);
			if($pengguna){
				if(!Hash::check($req->get('pengguna_sandi_lama'), $pengguna->pengguna_sandi)){
					return redirect()->back()
					->with('swal_pesan', 'Kata sandi lama salah')
                    ->with('swal_judul', 'Ganti Sandi')
					->with('swal_tipe', 'error');
				}
			}else{
				return redirect()->back()
				->with('swal_pesan', 'Gagal mengubah kata sandi. Data pengguna tidak tersedia')
                ->with('swal_judul', 'Ganti Sandi')
				->with('swal_tipe', 'error');
			}
			$pengguna = new Pengguna();
			$pengguna->exists = true;
			$pengguna->pengguna_id = Auth::user()->pegawai->nip;
			$pengguna->pengguna_sandi = Hash::make($req->get('pengguna_sandi_baru'));
			$pengguna->updated_operator = ucfirst(strtolower(explode(', ', Redis::get(Session::getId()))[0]));
			$pengguna->save();
			return redirect()->back()
			->with('swal_pesan', 'Berhasil mengubah kata sandi')
			->with('swal_judul', 'Ganti Sandi')
			->with('swal_tipe', 'success');
		}catch(\Exception $e){
			return redirect(url()->previous())
			->with('swal_pesan', $e->getMessage())
			->with('swal_judul', 'Ganti Sandi')
			->with('swal_tipe', 'error');
		}
	}

	private function foto($nip)
	{
		$pegawai = Pegawai::where('nip', $nip)->firstOrFail();
		$img = Image::make($pegawai->foto)->resize(200, null, function ($constraint) {
			$constraint->aspectRatio();
		})->crop(200, 200, 0, 15)->encode('png', 100);
		Storage::disk('public')->put('images/pegawai/'.$nip.'.png', $img);
		$img->destroy();
		return 'images/pegawai/'.$nip.'.png';
	}

	public function hapus($id)
	{
		try{
            $pengguna = Pengguna::findOrFail($id);
			$pengguna->delete();
			Storage::disk('public')->delete($pengguna->pengguna_foto);
			return response()->json([
				'swal_pesan' => 'Berhasil menghapus data pengguna ID: '.$id,
				'swal_judul' => 'Hapus data',
				'swal_tipe' => 'success',
			]);
		}catch(\Exception $e){
			return response()->json([
				'swal_pesan' => $e->getMessage(),
				'swal_judul' => 'Hapus data',
				'swal_tipe' =>'error',
			]);
		}
	}
}
