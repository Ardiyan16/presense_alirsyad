<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class DataUnitController extends Controller
{

    public function index(Request $request)
    {
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $draw   = $request->input('draw');
        $search = $request->input('search.value');

        $unitData = Unit::latest();

        if (!empty($search)) {
            $unitData = $unitData->where(function ($query) use ($search) {
                $query->where('unit', 'like', "%$search%");
            });
        }

        $total = $unitData->count();

        if ($total == 0) {
            $result['recordsTotal']     = 0;
            $result['recordsFiltered']  = 0;
            $result['draw']             = $draw;
            $result['data']             = [];
            $result['status']           = false;
            $result['message']          = "Data tidak ditemukan";
            return response($result);
        }

        $data = $unitData->offset($start)->limit($length)->get();

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
        $simpan = [
            'unit' => $request->unit,
        ];

        $insert = Unit::create($simpan);
        if($insert) {
            return response([
                'status' => true,
                'message' => 'Data unit berhasil disimpan',
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Data unit gagal disimpan',
        ]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $unit = $request->unit;

        $update = [
            'unit' => $unit
        ];

        $proses = Unit::where('id', $id)->update($update);

        if($proses) {
            return response([
                'status' => true,
                'message' => 'Data unit berhasil diubah'
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'Data unit gagal diubah'
            ]);
        }
    }

    public function destroy($id)
    {
        $hapus = Unit::where('id', $id)->delete();

        if($hapus) {

            return response([
                'status' => true,
                'message' => 'Data unit berhasil dihapus'
            ]);

        } else {

            return response([
                'status' => false,
                'message' => 'Data unit gagal dihapus',
            ]);
        }
    }
}
