<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Leave_reason;
use App\Models\Leave;
use App\Models\Permit;
use App\Models\User;


class CutiController extends Controller
{

    public function index(Request $request)
    {
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $draw   = $request->input('draw');
        $search = $request->input('search.value');

        $data_cuti = DB::table('leaves')
                        ->select('leaves.*', 'users.full_name', 'units.unit')
                        ->leftJoin('users', 'users.id', '=', 'leaves.user_id')
                        ->leftJoin('units', 'units.id', '=', 'users.unit_id')
                        ->orderBy('id', 'desc');

        if (!empty($search)) {
            $data_cuti = $data_cuti->where(function ($query) use ($search) {
                $query->where('full_name', 'like', "%$search%");
                $query->orWhere('unit', 'like', "%$search%");
            });
        }

        $total = $data_cuti->count();

        if ($total == 0) {
            $result['recordsTotal']     = 0;
            $result['recordsFiltered']  = 0;
            $result['draw']             = $draw;
            $result['data']             = [];
            $result['status']           = false;
            $result['message']          = "Data tidak ditemukan";
            return response($result);
        }

        $data = $data_cuti->offset($start)->limit($length)->get();

        $result['recordsTotal']     = $total;
        $result['recordsFiltered']  = $total;
        $result['draw']             = $draw;
        $result['data']             = $data;
        $result['status']           = true;
        $result['message']          = "OK";
        return response($result);
    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'user_id'       => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required',
            'reason_id'     => 'required',
            'information'   => 'required',
        ], [
            'user_id.required'      => 'Pegawai harus diisi',
            'start_date.required'   => 'Tanggal awal harus diisi',
            'end_date.required'     => 'Tanggal akhir harus diisi',
            'reason_id.required'   => 'Jenis cuti harus diisi',
            'information.required'  => 'Keterangan harus diisi'
        ]);

        if($validasi->fails()) {
            return response([
                'status' => false,
                'info_error' => true,
                'message' => $validasi->errors(),
            ]);
        }

        $cuti = [
            'user_id' => $request->user_id,
            'start_date' => $request->start_date,
            'end_date'  => $request->end_date,
            'reason_id' => $request->reason_id,
            'information' => $request->information,
        ];

        $simpan = Leave::create($cuti);

        if($simpan) {
            return response([
                'status' => true,
                'message' => 'Cuti berhasil disimpan'
            ]);
        }

        return response([
            'status' => false,
            'info_error' => false,
            'message' => 'Cuti gagal disimpan'
        ]);
    }

    public function get_edit($id)
    {
        $data_cuti = Leave::where('id', $id)->first();

        if($data_cuti) {
            return response([
                'status' => true,
                'data' => $data_cuti,
                'message' => 'Ok'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $validasi = Validator::make($request->all(), [
            'user_id'       => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required',
            'reason_id'     => 'required',
            'information'   => 'required',
        ], [
            'user_id.required'      => 'Pegawai harus diisi',
            'start_date.required'   => 'Tanggal awal harus diisi',
            'end_date.required'     => 'Tanggal akhir harus diisi',
            'reason_id.required'   => 'Jenis cuti harus diisi',
            'information.required'  => 'Keterangan harus diisi'
        ]);

        if($validasi->fails()) {
            return response([
                'status' => false,
                'info_error' => true,
                'message' => $validasi->errors(),
            ]);
        }

        $cuti = [
            'user_id' => $request->user_id,
            'start_date' => $request->start_date,
            'end_date'  => $request->end_date,
            'reason_id' => $request->reason_id,
            'information' => $request->information,
        ];

        $update = Leave::where('id', $id)->update($cuti);

        if($update) {
            return response([
                'status' => true,
                'message' => 'Cuti berhasil diubah'
            ]);
        }

        return response([
            'status' => false,
            'info_error' => false,
            'message' => 'Cuti gagal diubah'
        ]);
    }

    public function destroy($id)
    {
        $hapus = Leave::where('id', $id)->delete();

        if($hapus) {
            return response([
                'status' => true,
                'message' => 'Data cuti berhasil dihapus'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Data cuti gagal dihapus'
        ]);
    }

    public function get_pegawai()
    {
        $pegawai = User::where('role', 2)->get();

        if($pegawai) {
            return response([
                'status' => true,
                'data' => $pegawai,
                'message' => 'Ok'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'data tidak ditemukan'
        ]);
    }

    public function get_jenis_cuti()
    {
        $jenis_cuti = Leave_reason::all();

        if($jenis_cuti) {
            return response([
                'status' => true,
                'data' => $jenis_cuti,
                'message' => 'Ok'
            ]);
        }

        return response([
            'status' => false,
            'messafe' => 'Data tidak ditemukan',
        ]);
    }

    public function jenis_cuti(Request $request)
    {
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $draw   = $request->input('draw');
        $search = $request->input('search.value');

        $data_cuti = Leave_reason::latest();

        if (!empty($search)) {
            $data_cuti = $data_cuti->where(function ($query) use ($search) {
                $query->where('reason', 'like', "%$search%");
            });
        }

        $total = $data_cuti->count();

        if ($total == 0) {
            $result['recordsTotal']     = 0;
            $result['recordsFiltered']  = 0;
            $result['draw']             = $draw;
            $result['data']             = [];
            $result['status']           = false;
            $result['message']          = "Data tidak ditemukan";
            return response($result);
        }

        $data = $data_cuti->offset($start)->limit($length)->get();

        $result['recordsTotal']     = $total;
        $result['recordsFiltered']  = $total;
        $result['draw']             = $draw;
        $result['data']             = $data;
        $result['status']           = true;
        $result['message']          = "OK";
        return response($result);
    }

    public function simpan_jenis_cuti(Request $request)
    {
        $simpan = [
            'reason' => $request->reason
        ];

        $insert = Leave_reason::create($simpan);
        if($insert) {
            return response([
                'status' => true,
                'message' => 'data jenis cuti berhasil disimpan'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'data jenis cuti gagal disimpan',
        ]);

    }

    public function update_jenis_cuti(Request $request)
    {
        $id = $request->id;
        $edit = [
            'reason' => $request->reason
        ];

        $update = Leave_reason::where('id', $id)->update($edit);

        if($update) {
            return response([
                'status' => true,
                'message' => 'data jenis cuti berhasil diubah'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'data jenis cuti gagal diubah'
        ]);
    }

    public function hapus_jenis_cuti($id)
    {
        $hapus = Leave_reason::where('id', $id)->delete();

        if($hapus) {
            return response([
                'status' => true,
                'message' => 'data jenis cuti berhasil dihapus'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'data jenis cuti gagal dihapus'
        ]);
    }
}
