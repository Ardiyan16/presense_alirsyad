<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseFormatter;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login_action(Request $request)
    {
        // $validate = Validator::make($request->all(), [
        //     'email'     => 'email|required|exists:users,email',
        //     'password'  => 'string|required'
        // ], [
        //     'required' => 'email dan password harus diisi',
        //     'exists' => $request->email . ' tidak terdaftar di sistem kami, silahkan gunakan email valid',
        // ]);

        // if ($validate->fails()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Password atau username anda salah'
        //     ], 401);
        // }
    }
}
