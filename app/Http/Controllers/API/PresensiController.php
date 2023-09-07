<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Unit;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Permit;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $draw   = $request->input('draw');
        $search = $request->input('search.value');
        $bulan  = $request->bulan;
        $tahun  = $request->tahun;


        if (!empty($search)) {
            $dataPegawai = DB::table('users')
                        ->select('users.*', 'units.unit')
                        ->leftJoin('units', 'units.id', '=', 'users.unit_id')
                        ->where('role', 2)
                        ->where('name', 'like', "%$search%")
                        ->orWhere('full_name', 'like', "%$search%")
                        ->orWhere('unit', 'like', "%$search%")
                        ->orderBy('id', 'desc')
                        ->get();

        } else {
            $dataPegawai = DB::table('users')
                    ->select('users.*', 'units.unit')
                    ->leftJoin('units', 'units.id', '=', 'users.unit_id')
                    ->where('role', 2)
                    ->orderBy('id', 'desc')
                    ->get();
            }

        $no_presensi = 0;
        $bulanini = date('m');
        $tahunini = date('Y');
        foreach($dataPegawai as $value) {

            if(!empty($bulan) && !empty($tahun)) {
                $jml_absen_masuk = Attendance::where([
                    ['user_id', $value->id],
                    ['presence_type', 1]
                ])
                ->whereMonth('date_presence', '=', $bulan)
                ->whereYear('date_presence', '=', $tahun)
                ->count();

                $jml_absen_pulang = Attendance::where([
                    ['user_id', $value->id],
                    ['presence_type', 2]
                ])
                ->whereMonth('date_presence', '=', $bulan)
                ->whereYear('date_presence', '=', $tahun)
                ->count();

                $jml_izin = Permit::where('user_id', $value->id)->whereMonth('date', '=', $bulan)->whereYear('date', '=', $tahun)->count();

            } else {
                $jml_absen_masuk = Attendance::where([
                    ['user_id', $value->id],
                    ['presence_type', 1]
                ])
                ->whereMonth('date_presence', '=', $bulanini)
                ->whereYear('date_presence', '=', $tahunini)
                ->count();

                $jml_absen_pulang = Attendance::where([
                    ['user_id', $value->id],
                    ['presence_type', 2]
                ])
                ->whereMonth('date_presence', '=', $bulanini)
                ->whereYear('date_presence', '=', $tahunini)
                ->count();

                $jml_izin = Permit::where('user_id', $value->id)->whereMonth('date', '=', $bulanini)->whereYear('date', '=', $tahunini)->count();
            }

            $dataAbsen[] = [
                'id' => $value->id,
                'nama_lengkap' => $value->full_name,
                'unit' => $value->unit,
                'jml_absen_masuk' => $jml_absen_masuk,
                'jml_absen_keluar' => $jml_absen_pulang,
                'jml_izin' => $jml_izin,
            ];
        }


        if(empty($dataAbsen)) {
            $result['recordsTotal']     = 0;
            $result['recordsFiltered']  = 0;
            $result['draw']             = $draw;
            $result['data']             = [];
            $result['status']           = false;
            $result['message']          = "Data tidak ditemukan";
            return response($result);
        }

        // $data = $dataPegawai->offset($start)->limit($length)->get();
        $total = count($dataAbsen);
        $data = array_slice($dataAbsen, $start, $length);

        $result['recordsTotal']     = $total;
        $result['recordsFiltered']  = $total;
        $result['draw']             = $draw;
        // $result['dataabsen']        = $dataa;
        $result['data']             = $data;
        $result['status']           = true;
        $result['message']          = "OK";

        return response($result);
    }

    public function get_user_id($id)
    {
        $user = User::where('id', $id)->first();

        if($user) {
            return response([
                'status' => true,
                'data' => $user,
                'message' => 'Ok'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Failed'
        ]);
    }

    public function detail_presensi_masuk(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $start  = $request->input('start', 0);
        $length = $request->input('length', 30);
        $draw   = $request->input('draw');
        $search = $request->input('search.value');
        $user_id = $request->user_id;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $bulanini = date('m');
        $tahunini = date('Y');

        if(!empty($bulan) && !empty($tahun)) {
            $presensi = Attendance::where([
                            ['user_id', $user_id],
                            ['presence_type', 1]
                        ])
                        ->whereMonth('date_presence', '=', $bulan)
                        ->whereYear('date_presence', '=', $tahun)
                        ->get();

        } else {
            $presensi = Attendance::where([
                            ['user_id', $user_id],
                            ['presence_type', 1]
                        ])
                        ->whereMonth('date_presence', '=', $bulanini)
                        ->whereYear('date_presence', '=', $tahunini)
                        ->get();

        }

        foreach($presensi as $value) {

            $tanggal = $value->date_presence;
            $hari = date('D', strtotime($tanggal));
            $list_hari = [
                'Sun' => 'Minggu',
                'Mon' => 'Senin',
                'Tue' => 'Selasa',
                'Wed' => 'Rabu',
                'Thu' => 'Kamis',
                'Fri' => 'Jumat',
                'Sat' => 'Sabtu'
            ];

            $hari_tanggal = $list_hari[$hari].', '. date('d-m-Y', strtotime($tanggal));

            $datapresensi[] = [
                'tanggal_absen' => $hari_tanggal,
                'waktu' => $value->time,
                'status' => $value->status,
                'lokasi' => $value->presence_status
            ];
        }

        if(empty($datapresensi)) {
            $result['recordsTotal']     = 0;
            $result['recordsFiltered']  = 0;
            $result['draw']             = $draw;
            $result['data']             = [];
            $result['status']           = false;
            $result['message']          = "Data tidak ditemukan";
            return response($result);
        }


        $total = count($datapresensi);
        $data = array_slice($datapresensi, $start, $length);

        $result['recordsTotal']     = $total;
        $result['recordsFiltered']  = $total;
        $result['draw']             = $draw;
        $result['data']             = $data;
        $result['status']           = true;
        $result['message']          = "OK";

        return response($result);

    }

    public function detail_presensi_keluar(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $start  = $request->input('start', 0);
        $length = $request->input('length', 30);
        $draw   = $request->input('draw');
        $search = $request->input('search.value');
        $user_id = $request->user_id;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $bulanini = date('m');
        $tahunini = date('Y');

        if(!empty($bulan) && !empty($tahun)) {
            $presensi = Attendance::where([
                            ['user_id', $user_id],
                            ['presence_type', 2]
                        ])
                        ->whereMonth('date_presence', '=', $bulan)
                        ->whereYear('date_presence', '=', $tahun)
                        ->get();

        } else {
            $presensi = Attendance::where([
                            ['user_id', $user_id],
                            ['presence_type', 2]
                        ])
                        ->whereMonth('date_presence', '=', $bulanini)
                        ->whereYear('date_presence', '=', $tahunini)
                        ->get();

        }

        foreach($presensi as $value) {

            $tanggal = $value->date_presence;
            $hari = date('D', strtotime($tanggal));
            $list_hari = [
                'Sun' => 'Minggu',
                'Mon' => 'Senin',
                'Tue' => 'Selasa',
                'Wed' => 'Rabu',
                'Thu' => 'Kamis',
                'Fri' => 'Jumat',
                'Sat' => 'Sabtu'
            ];

            $hari_tanggal = $list_hari[$hari].', '. date('d-m-Y', strtotime($tanggal));

            $datapresensi[] = [
                'tanggal_absen' => $hari_tanggal,
                'waktu' => $value->time,
                'status' => $value->status,
                'lokasi' => $value->presence_status
            ];
        }

        if(empty($datapresensi)) {
            $result['recordsTotal']     = 0;
            $result['recordsFiltered']  = 0;
            $result['draw']             = $draw;
            $result['data']             = [];
            $result['status']           = false;
            $result['message']          = "Data tidak ditemukan";
            return response($result);
        }


        $total = count($datapresensi);
        $data = array_slice($datapresensi, $start, $length);

        $result['recordsTotal']     = $total;
        $result['recordsFiltered']  = $total;
        $result['draw']             = $draw;
        $result['data']             = $data;
        $result['status']           = true;
        $result['message']          = "OK";

        return response($result);
    }

    public function detail_data_izin(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $start  = $request->input('start', 0);
        $length = $request->input('length', 30);
        $draw   = $request->input('draw');
        $search = $request->input('search.value');
        $user_id = $request->user_id;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $bulanini = date('m');
        $tahunini = date('Y');

        if(!empty($bulan) && !empty($tahun)) {
            $izin = Permit::where('user_id', $user_id)
                    ->whereMonth('date', '=', $bulan)
                    ->whereYear('date', '=', $tahun)
                    ->get();
        } else {
            $izin = Permit::where('user_id', $user_id)
                    ->whereMonth('date', '=', $bulanini)
                    ->whereYear('date', '=', $tahunini)
                    ->get();
        }

        foreach($izin as $value) {

            $tanggal = $value->date;
            $hari = date('D', strtotime($tanggal));
            $list_hari = [
                'Sun' => 'Minggu',
                'Mon' => 'Senin',
                'Tue' => 'Selasa',
                'Wed' => 'Rabu',
                'Thu' => 'Kamis',
                'Fri' => 'Jumat',
                'Sat' => 'Sabtu'
            ];

            $hari_tanggal = $list_hari[$hari].', '. date('d-m-Y', strtotime($tanggal));

            $dataizin[] = [
                'tanggal' => $hari_tanggal,
                'waktu' => $value->time,
                'type_permit' => $value->type_permit,
                'necessity' => $value->necessity
            ];
        }

        if(empty($dataizin)) {
            $result['recordsTotal']     = 0;
            $result['recordsFiltered']  = 0;
            $result['draw']             = $draw;
            $result['data']             = [];
            $result['status']           = false;
            $result['message']          = "Data tidak ditemukan";
            return response($result);
        }


        $total = count($dataizin);
        $data = array_slice($dataizin, $start, $length);

        $result['recordsTotal']     = $total;
        $result['recordsFiltered']  = $total;
        $result['draw']             = $draw;
        $result['data']             = $data;
        $result['status']           = true;
        $result['message']          = "OK";

        return response($result);
    }
}
