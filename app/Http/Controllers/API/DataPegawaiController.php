<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DataPegawaiController extends Controller
{
    public function index(Request $request)
    {
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $draw   = $request->input('draw');
        $search = $request->input('search.value');

        $dataPegawai = DB::table('users')
                        ->select('users.*', 'units.unit')
                        ->leftJoin('units', 'units.id', '=', 'users.unit_id')
                        ->where('role', 2)
                        ->orderBy('id', 'desc');

        if (!empty($search)) {
            $dataPegawai = $dataPegawai->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                        ->orWhere('full_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
            });
        }

        $total = $dataPegawai->count();

        if($total == 0) {
            $result['recordsTotal']     = 0;
            $result['recordsFiltered']  = 0;
            $result['draw']             = $draw;
            $result['data']             = [];
            $result['status']           = false;
            $result['message']          = "Data tidak ditemukan";
            return response($result);
        }

        $data = $dataPegawai->offset($start)->limit($length)->get();

        $result['recordsTotal']     = $total;
        $result['recordsFiltered']  = $total;
        $result['draw']             = $draw;
        $result['data']             = $data;
        $result['status']           = true;
        $result['message']          = "OK";

        return response($result);
    }

    public function get_unit()
    {
        $unit = Unit::all();

        if($unit) {

            return response([
                'status' => true,
                'data' => $unit,
                'message' => 'OK'
            ]);

        } else {

            return response([
                'status' => false,
                'message' => 'data tidak ditemukan'
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'full_name'     => 'required',
            'name'          => 'required',
            'username'      => 'required',
            'email'         => 'required|email|unique:users,email',
            'unit_id'       => 'required',
        ], [
            'full_name.required' => 'Nama lengkap wajib diisi',
            'name.required' => 'Nama panggilan wajib diisi',
            'username.required' => 'username wajib diisi',
            'email.required' => 'email wajib diisi',
            'email.email' => 'email harus valid',
            'unique' => 'email telah terdaftar',
            'unit_id.required' => 'Unit wajib diisi',
        ]);

        if($validator->fails()) {
            return response([
                'status' => false,
                'info_error' => 'validasi_eror',
                'errors' => $validator->errors(),
            ]);
        }

        date_default_timezone_set('Asia/Jakarta');
        $password = '12345';
        $pegawai = [
            'name'              => $request->name,
            'full_name'         => $request->full_name,
            'username'          => $request->username,
            'email'             => $request->email,
            'email_verifed_at'  => date('Y-m-d H:i:s'),
            'password'          => Hash::make($password),
            'unit_id'           => $request->unit_id,
            'status'            => '1',
            'role'              => '2',
            'remember_token'    => Str::random(60),
        ];

        $save = User::create($pegawai);

        if($save) {
            return response([
                'status' => true,
                'message' => 'Data pegawai berhasil disimpan'
            ]);
        }

        return response([
            'status' => false,
            'info_error' => 'failed_save',
            'message' => 'Data pegawai gagal disimpan'
        ]);

    }

    public function get_edit($id)
    {
        $pegawai = User::where('id', $id)->first();

        if($pegawai) {
            return response([
                'status' => true,
                'data' => $pegawai,
                'message' => 'Ok'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Data kosong'
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'full_name'     => 'required',
            'name'          => 'required',
            'username'      => 'required',
            'email'         => 'required|email',
            'unit_id'       => 'required',
        ], [
            'full_name.required' => 'Nama lengkap wajib diisi',
            'name.required' => 'Nama panggilan wajib diisi',
            'username.required' => 'username wajib diisi',
            'email.required' => 'email wajib diisi',
            'email.email' => 'email harus valid',
            'unit_id.required' => 'Unit wajib diisi',
        ]);


        if($validator->fails()) {
            return response([
                'status' => false,
                'info_error' => 'validasi_eror',
                'errors' => $validator->errors(),
            ]);
        }

        $email = $request->email;
        $id = $request->id;
        $cek_email = User::where('email', $email)->first();
        if($cek_email) {
            return response([
                'status' => false,
                'message' => 'email telah digunakan',
            ]);
        }

        $pegawai = [
            'name'              => $request->name,
            'full_name'         => $request->full_name,
            'username'          => $request->username,
            'email'             => $email,
            'unit_id'           => $request->unit_id,
        ];

        $update = User::where('id', $id)->update($pegawai);

        if($update) {
            return response([
                'status' => true,
                'message' => 'Data pegawai berhasil diubah'
            ]);
        }

        return response([
            'status' => false,
            'info_error' => 'failed_save',
            'message' => 'Data pegawai gagal diubah'
        ]);
    }

    public function nonaktif(Request $request)
    {
        $id = $request->id;
        $nonaktif = [
            'status'    => '0'
        ];

        $user_data = User::where('id', $id)->first();
        $update = User::where('id', $id)->update($nonaktif);
        if($update) {
            return response([
                'status' => true,
                'message' => $user_data->full_name . ' berhasil di non aktifkan'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Gagal di non aktifkan'
        ]);
    }

    public function aktifkan(Request $request)
    {
        $id = $request->id;
        $aktifkan = [
            'status'    => '1'
        ];

        $user_data = User::where('id', $id)->first();
        $update = User::where('id', $id)->update($aktifkan);
        if($update) {
            return response([
                'status' => true,
                'message' => $user_data->full_name . ' berhasil di aktifkan'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Gagal di aktifkan'
        ]);
    }

    public function destroy($id)
    {
        $hapus = User::where('id', $id)->delete();

        if($hapus) {
            return response([
                'status' => true,
                'message' => 'Data pegawai berhasil dihapus'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Data pegawai gagal dihapus'
        ]);
    }

    public function get_profile($id)
    {
        $profile = $dataPegawai = DB::table('users')
                                    ->select('users.*', 'units.unit')
                                    ->leftJoin('units', 'units.id', '=', 'users.unit_id')
                                    ->where('users.id', $id)
                                    ->first();

        if($profile) {
            return response([
                'status' => true,
                'data'  => $profile,
                'message' => 'OK'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Data tidak tersedia'
        ]);
    }

    public function update_profile(Request $request)
    {
        
        $validator = Validator::make($request->all(),[
            'full_name'     => 'required',
            'name'          => 'required',
            'username'      => 'required',
        ], [
            'full_name.required' => 'Nama lengkap wajib diisi',
            'name.required' => 'Nama panggilan wajib diisi',
            'username.required' => 'username wajib diisi',
        ]);


        if($validator->fails()) {
            return response([
                'status' => false,
                'info_error' => 'validasi_eror',
                'errors' => $validator->errors(),
            ]);
        }

        $id = $request->id;
        $profile = [
            'full_name' => $request->full_name,
            'name'      => $request->name,
            'username'  => $request->username
        ];

        $update = User::where('id', $id)->update($profile);

        if($update) {
            return response([
                'status' => true,
                'message' => 'Profile anda berhasil diubah'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Profile anda gagal diubah'
        ]);
    }

}
