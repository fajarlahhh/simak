<?php

namespace App\Http\Controllers;

use App\Bidang;
use App\Gambar;
use App\Jabatan;
use App\Pegawai;
use App\Pengguna;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    //
	public function __construct()
	{
        $this->middleware('auth');
	}

	public function index(Request $req)
	{
		$data = Pengguna::with('jabatan')->where(function($q) use ($req){
            $q->where('pengguna_nama', 'like', '%'.$req->cari.'%')->orWhere('pengguna_id', 'like', '%'.$req->cari.'%');
        });

        switch ($req->tipe) {
            case '1':
                $data = $data->onlyTrashed();
                break;
            case '2':
                $data = $data->withTrashed();
                break;
        }

        $data = $data->paginate(10);

        $data->appends(['cari' => $req->tipe, 'cari' => $req->tipe]);
		return view('pages.setup.pengguna.index', [
            'data' => $data,
            'i' => ($req->input('page', 1) - 1) * 10,
            'cari' => $req->cari,
            'tipe' => $req->tipe,
        ]);
    }

    private function menu()
    {
        $menu = [];
        foreach (config('sidebar.menu') as $key => $row) {
            $sub_menu = [];
            if ($row['title'] != 'Dashboard') {
                if (!empty($row['sub_menu'])) {
                    foreach ($row['sub_menu'] as $key => $sub) {
                        array_push($sub_menu, [
                            'id' => $sub['id'],
                            'value' => $sub['id'],
                            'title' => $sub['title']
                        ]);
                    }
                }
                array_push($menu, [
                    'value' => !empty($row['sub_menu'])? strtolower($row['title']) : $row['id'],
                    'title' => $row['title'],
                    'id' => $row['id'],
                    'sub' => $sub_menu
                ]);
            }
        }

        return $menu;
    }

	public function tambah()
	{
        return view('pages.setup.pengguna.form', [
            'jabatan' => Jabatan::all(),
            'level' => Role::all(),
            'gambar' => Gambar::all(),
            'back' => Str::contains(url()->previous(), ['datapengguna/tambah', 'datapengguna/edit'])? '/datapengguna': url()->previous(),
            'menu' => $this->menu(),
            'aksi' => 'Tambah',
            'i' => 0
        ]);
	}

	public function do_tambah(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'pengguna_id' => 'required',
                'pengguna_sandi' => 'required|min:5',
                'pengguna_level' => 'required',
                'pengguna_nama' => 'required',
                'jabatan_id' => 'required'
            ],[
                'pengguna_id.required' => 'Pegawai tidak boleh kosong',
                'pengguna_sandi.min' => 'Kata Sandi minimal 5 karakter',
                'pengguna_sandi.required'  => 'Kata Sandi tidak boleh kosong',
                'pengguna_level.required'  => 'Level tidak boleh kosong',
                'pengguna_nama.required'  => 'Nama tidak boleh kosong',
                'jabatan_id.required'  => 'Jabatan tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }
		try{
			$pengguna = new Pengguna();
			$pengguna->pengguna_id = $req->get('pengguna_id');
			$pengguna->pengguna_nama = $req->get('pengguna_nama');
			$pengguna->pengguna_nip = $req->get('pengguna_nip');
			$pengguna->pengguna_hp = $req->get('pengguna_hp');
			$pengguna->pengguna_sandi = Hash::make($req->get('pengguna_sandi'));
			$pengguna->jabatan_id = $req->get('jabatan_id');
            $pengguna->pengguna_pangkat = $req->get('pengguna_pangkat');
			$pengguna->gambar_nama = $req->get('gambar_nama');
            $pengguna->token = Hash::make($req->get('pengguna_id'));
			$pengguna->save();
			$pengguna->assignRole($req->get('pengguna_level'));

			$pengguna->givePermissionTo('dashboard');
			if($req->get('izin')){
				for ($i=0; $i < sizeof($req->get('izin')); $i++) {
					$pengguna->givePermissionTo($req->get('izin')[$i]);
				}
			}
            toast('Berhasil menambah data pengguna '.$req->get('pengguna_id'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): 'pengguna');
		}catch(\Exception $e){
            alert()->error('Tambah Data', $e->getMessage());
            return redirect()->back()->withInput();
		}
	}

	public function edit($id)
	{
		try{
			return view('pages.setup.pengguna.form', [
                'jabatan' => Jabatan::all(),
                'data' => Pengguna::findOrFail($id),
                'gambar' => Gambar::all(),
                'bidang' => Bidang::all(),
                'level' => (in_array($id, config('admin.nip'))? Role::where('name', 'super-admin')->get(): Role::all()),
                'back' => Str::contains(url()->previous(), 'datapengguna/edit')? '/datapengguna': url()->previous(),
                'aksi' => 'Edit',
                'menu' => $this->menu(),
                'i' => 0
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
        $validator = Validator::make($req->all(),
            [
                'pengguna_id' => 'required',
                'pengguna_level' => 'required',
                'pengguna_nama' => 'required',
                'jabatan_id' => 'required'
            ],[
                'pengguna_id.required' => 'Pegawai tidak boleh kosong',
                'pengguna_level.required'  => 'Level tidak boleh kosong',
                'pengguna_nama.required'  => 'Nama tidak boleh kosong',
                'jabatan_id.required'  => 'Jabatan tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }
		try{
            $pengguna = Pengguna::findOrFail($req->get('ID'));
			$pengguna->pengguna_nama = $req->get('pengguna_nama');
			$pengguna->pengguna_hp = $req->get('pengguna_hp');
			$pengguna->pengguna_nip = $req->get('pengguna_nip');
			if ($req->get('pengguna_sandi')) {
				$pengguna->pengguna_sandi = Hash::make($req->get('pengguna_sandi'));
			}
			$pengguna->jabatan_id = $req->get('jabatan_id');
			$pengguna->pengguna_pangkat = $req->get('pengguna_pangkat');
			$pengguna->gambar_nama = $req->get('gambar_nama');
			$pengguna->save();
            $pengguna->syncPermissions();
			$pengguna->removeRole($pengguna->getRoleNames()[0]);
            $pengguna->assignRole($req->get('pengguna_level'));

			$pengguna->givePermissionTo('dashboard');
			if($req->get('izin')){
				for ($i=0; $i < sizeof($req->get('izin')); $i++) {
					$pengguna->givePermissionTo($req->get('izin')[$i]);
				}
			}
            toast('Berhasil mengedit data pengguna '.$req->get('pengguna_id'), 'success')->autoClose(2000);
			return redirect($req->get('redirect')? $req->get('redirect'): 'pengguna');
		}catch(\Exception $e){
            alert()->error('Edit Data', $e->getMessage());
            return redirect()->back()->withInput();
		}
	}

	public function ganti_sandi($value='')
	{
		try{
            return view('includes.component.modal-password');
        }catch(\Exception $e){
            alert()->error('Edit Data', $e->getMessage());
            return back();
        }
    }

	public function do_ganti_sandi(Request $req)
	{
        $validator = Validator::make($req->all(),
            [
                'pengguna_sandi_baru' => 'required|min:5',
                'pengguna_sandi_lama' => 'required|min:5',
            ],[
                'pengguna_sandi_lama.required' => 'Sandi Lama tidak boleh kosong',
                'pengguna_sandi_lama.min' => 'Sandi Lama minimal 5 karakter',
                'pengguna_sandi_baru.required'  => 'Sandi Baru tidak boleh kosong',
                'pengguna_sandi_baru.min' => 'Sandi Baru minimal 5 karakter',
            ]
        );

        if ($validator->fails()) {
            alert()->error('Validasi Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return redirect()->back()->withInput()->with('error', $validator->messages()->all());
        }

		try{
            $id = Auth::id();
			$pengguna = Pengguna::findOrFail($id);
			if($pengguna){
				if(!Hash::check($req->get('pengguna_sandi_lama'), $pengguna->pengguna_sandi)){
                    alert()->error('Ganti Sandi', 'Gagal mengganti kata sandi. Kata sandi lama salah')->autoClose(3000);
					return redirect()->back();
				}
			}else{
                alert()->error('Ganti Sandi', 'Gagal mengganti kata sandi. Data pengguna tidak tersedia')->autoClose(3000);
				return redirect()->back();
			}
			$pengguna->pengguna_sandi = Hash::make($req->get('pengguna_sandi_baru'));
			$pengguna->save();
            toast('Berhasil mengganti kata sandi', 'success')->autoClose(2000);
			return redirect()->back();
		}catch(\Exception $e){
            alert()->error('Ganti Sandi', $e->getMessage());
			return redirect(url()->previous());
		}
	}

	public function hapus($id)
	{
		try{
            $pengguna = Pengguna::findOrFail($id);
			$pengguna->delete();
            toast('Berhasil menghapus data '.$id, 'success')->autoClose(2000);
		}catch(\Exception $e){
            alert()->error('Hapus Data', $e->getMessage());
		}
	}

	public function restore($id)
	{
		try{
            Pengguna::withTrashed()->findOrFail($id)->restore();
            toast('Berhasil mengembalikan data', 'success')->autoClose(2000);
		}catch(\Exception $e){
            alert()->error('Restore Data', $e->getMessage());
		}
	}
}
