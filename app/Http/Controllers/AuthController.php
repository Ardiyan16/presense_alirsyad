<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login_action(Request $request)
    {
        $email = $request->email;
        $cek_akun = User::where('email', $email)->first();

        // dd(!Hash::check($request->password, $cek_akun->password));
        if(!$cek_akun || !Hash::check($request->password, $cek_akun->password)) {
            return response([
                'success' => false,
                'message' => 'Email atau Password yang anda masukkan salah!'
            ]);
        }

        if($cek_akun->status == '0') {
            return response([
                'success' => false,
                'message' => 'Akun anda belum di aktifkan / nonaktif'
            ]);
        }

        $redirect = '';
        if($cek_akun->role == 1) {
            $redirect = 'admin';
        } else {
            $redirect = 'user';
        }

        return response([
            'success' => true,
            'message' => 'Anda akan dialihkan dalam 3 detik',
            'redirect' => $redirect,
            'remember_token' => $cek_akun->remember_token
        ]);

    }

    public function logout()
    {
        Auth::logout();
        Cookie::queue(Cookie::forget('ALD_SESSION'));
        return redirect()->route('login');
    }

    public function lupa_password()
    {
        return view('auth.lupa_pass');
    }

    public function action_lupa_password(Request $request)
    {
        $email = $request->email;
        $cek_akun = User::where('email', $email)->first();

        if(!$cek_akun) {
            return response([
                'success' => false,
                'message' => 'email anda belum terdaftar'
            ]);
        }

        $password = [
            'password' => Hash::make($request->password)
        ];

        $update = User::where('id', $cek_akun->id)->update($password);
        if($update) {
            return response([
                'success' => true,
                'message' => 'password anda berhasil diubah'
            ]);
        }

        return response([
            'success' => false,
            'message' => 'password anda gagal diubah'
        ]);
    }

}
