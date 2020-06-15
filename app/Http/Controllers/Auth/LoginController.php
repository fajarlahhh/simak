<?php

namespace App\Http\Controllers\Auth;

use App\Pengguna;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo()
    {
        return '/';
    }

    public function showLoginForm(){
        if(!session()->has('url.intended'))
        {
            session(['url.intended' => url()->previous()]);
        }
        return view('pages.login');
    }

    public function login( Request $req)
    {
        $validator = Validator::make($req->all(), [
            'uid' => 'required',
            'password' => 'required'
        ],[
            'uid.required'  => 'ID Pengguna tidak boleh kosong',
            'password.required'  => 'Kata Sandi tidak boleh kosong'
        ]);

        if ($validator->fails()) {
            alert()->error('Login Gagal', implode('<br>', $validator->messages()->all()))->toHtml()->autoClose(5000);
            return Redirect::back()->withInput();
        }

        $remember = ($req->remember == 'on') ? true : false;

        if (Auth::attempt(['pengguna_id' => $req->uid, 'password' => $req->password], $remember)) {

            // $new_session_id = Session::getId();
            //

            // if($pengguna->session_id != '') {
            //     $last_session = Session::getHandler()->read($pengguna->session_id);
            //     if ($last_session) {
            //         if (Session::getHandler()->destroy($pengguna->session_id)) {

            //         }
            //     }
            // }

            // $pengguna->session_id = $new_session_id;
            // $pengguna->save();
            if($req->notif){
                $pengguna = Pengguna::findOrFail($req->uid);
                $pengguna->notif_id = $req->notif;
                $pengguna->save();
                $data = [
                    'pengguna_id' => $pengguna->pengguna_id,
                    'pengguna_nama' => $pengguna->pengguna_nama,
                    'notif_id' => $pengguna->notif_id,
                    'pengguna_nama' => $pengguna->pengguna_nama,
                    'pengguna_nama' => $pengguna->pengguna_nama,
                    'jabatan_id' => $pengguna->jabatan_id,
                    'jabatan_nama' => $pengguna->jabatan? $pengguna->jabatan->jabatan_nama: null,
                    'jabatan_pimpinan' => $pengguna->jabatan? $pengguna->jabatan->jabatan_pimpinan: null,
                    'jabatan_verifikator' => $pengguna->jabatan? $pengguna->jabatan->jabatan_verifikator: null,
                    'atasan_jabatan_id' => $pengguna->jabatan? ($pengguna->jabatan->atasan? $pengguna->jabatan->atasan->jabatan_id: null): null,
                    'atasan_jabatan_pimpinan' => $pengguna->jabatan? ($pengguna->jabatan->atasan? $pengguna->jabatan->atasan->jabatan_pimpinan: null): null,
                    'atasan_jabatan_verifikator' => $pengguna->jabatan? ($pengguna->jabatan->atasan? $pengguna->jabatan->atasan->jabatan_verifikator: null): null
                ];
                return response()->json($data);
            }else{
                return redirect()->intended('dashboard')
                ->with([
                    'gritter_judul' => 'Selamat datang ',
                    'gritter_teks' => 'Selamat bekerja dan semoga sukses',
                    'gritter_gambar' => url('public/assets/img/user/user.png')
                    ]);
            }
        }
        if($req->notif){
            return response()->json(null);
        }else{
            alert()->error('Login Gagal','ID Pengguna atau Kata Sandi salah');
            return Redirect::back()->withInput();
        }
    }

    private function username()
    {
        return 'pengguna_id';
    }
}
