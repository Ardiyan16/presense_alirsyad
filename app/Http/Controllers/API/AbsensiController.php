<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Permit;

class AbsensiController extends Controller
{
    public function presence_in(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $hari_ini = date('Y-m-d');

        $user_id = $request->user_id;
        $presence_status = $request->presence_status;
        $longitude = $request->longitude;
        $latitude = $request->latitude;
        $long_alirsyad = '113.717';
        $lat_alirsyad = '-8.179';

        $cek_absensi = Attendance::where([
            ['user_id', '=', $user_id],
            ['date_presence', 'LIKE', "{$hari_ini}%"],
            ['presence_type', '=', '1'],
        ])->first();

        $cek_izin = Permit::where([
            ['user_id', '=', $user_id],
            ['date', 'LIKE', "{$hari_ini}%"],
        ])->first();

        if($cek_absensi) {
            return response([
                'status' => false,
                'info' => true,
                'message' => 'Anda sudah absen hari ini',
            ]);
        }

        if($cek_izin->type_permit == 'tidak hadir') {
            return response([
                'status' => false,
                'info' => true,
                'message' => 'Anda sudah melakukan izin tidak hadir, maka tidak dapat melakukan absensi'
            ]);
        }

        if($presence_status == "in_office") {
            $cek_long = round($longitude, 3);
            $cek_lat = round($latitude, 3);
            if(($cek_long == $long_alirsyad) && ($cek_lat == $lat_alirsyad)) {

                $waktu_sekarang = date('H:i:s');
                if($waktu_sekarang > '07:35:00') {
                    $status = 'Late';
                } else {
                    $status = 'ok';
                }


                $presence_data = [
                    'user_id'           => $user_id,
                    'longitude'         => $longitude,
                    'latitude'          => $latitude,
                    'location_details'   => 'Jl. Karimata Gg. Barokah No.53, Gumuk Kerang, Sumbersari, Kec. Sumbersari, Kabupaten Jember, Jawa Timur 68121',
                    'status'            => $status,
                    'presence_type'     => '1',
                    'presence_status'   => $presence_status,
                    'date_presence'     => date('Y-m-d'),
                    'time'              => $waktu_sekarang,
                ];

                $simpan = Attendance::create($presence_data);
                if($simpan) {
                    return response([
                        'status' => true,
                        'message' => 'Kehadiran berhasil disimpan'
                    ]);
                } else {
                    return response([
                        'status' => false,
                        'nessage' => 'Kehadiran gagal disimpan'
                    ]);
                }

            } else {
                return response([
                    'status' => false,
                    'message' => 'lokasi anda berada diluar alirsyad jember, silahkan coba kembali'
                ]);
            }

        } else {

            $pegawai = User::where('id', $user_id)->first();
            $waktu_sekarang = date('H:i:s');
            if($waktu_sekarang > '07:35:00') {
                $status = 'Late';
            } else {
                $status = 'Ok';
            }
            $presence_data = [
                'user_id'           => $user_id,
                'longitude'         => $longitude,
                'latitude'          => $latitude,
                'location_details'   => 'Rumah ' . $pegawai->full_name,
                'status'            => $status,
                'presence_type'     => '1',
                'presence_status'   => $presence_status,
                'date_presence'     => date('Y-m-d'),
                'time'              => $waktu_sekarang,
            ];

            $simpan = Attendance::create($presence_data);
            if($simpan) {
                return response([
                    'status' => true,
                    'message' => 'Kehadiran berhasil disimpan'
                ]);
            } else {
                return response([
                    'status' => false,
                    'nessage' => 'Kehadiran gagal disimpan'
                ]);
            }
        }

    }

    public function presence_out(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $hari_ini = date('Y-m-d');
        $user_id = $request->user_id;
        $longitude = $request->longitude;
        $latitude = $request->latitude;
        $long_alirsyad = '113.717';
        $lat_alirsyad = '-8.179';

        $cek_absensi = Attendance::where([
            ['user_id', '=', $user_id],
            ['date_presence', 'LIKE', "{$hari_ini}%"],
            ['presence_type', '=', '1'],
        ])->first();

        $cek_absensi2 = Attendance::where([
            ['user_id', '=', $user_id],
            ['date_presence', 'LIKE', "{$hari_ini}%"],
            ['presence_type', '=', '2'],
        ])->first();

        $cek_izin = Permit::where([
            ['user_id', '=', $user_id],
            ['date', 'LIKE', "{$hari_ini}%"],
        ])->first();

        if(!$cek_absensi) {
            return response([
                'status' => false,
                'info' => true,
                'message' => 'Anda belum melakukan absensi masuk',
            ]);
        }

        if($cek_absensi2) {
            return response([
                'status' => false,
                'info' => true,
                'message' => 'Anda sudah melakukan absensi pulang',
            ]);
        }

        if($cek_izin->type_permit == 'tidak hadir') {
            return response([
                'status' => false,
                'info' => true,
                'message' => 'Anda sudah melakukan izin tidak hadir, maka tidak dapat melakukan absensi'
            ]);
        }

        $cek_long = round($longitude, 3);
        $cek_lat = round($latitude, 3);
        $waktu_sekarang = date('H:i:s');

        if(($cek_long == $long_alirsyad) && ($cek_lat == $lat_alirsyad)) {

            $presence_data = [
                'user_id'           => $user_id,
                'longitude'         => $longitude,
                'latitude'          => $latitude,
                'location_details'   => 'Jl. Karimata Gg. Barokah No.53, Gumuk Kerang, Sumbersari, Kec. Sumbersari, Kabupaten Jember, Jawa Timur 68121',
                'status'            => 'ok',
                'presence_type'     => '2',
                'presence_status'   => 'in_office',
                'date_presence'     => date('Y-m-d'),
                'time'              => $waktu_sekarang,
            ];

        } else {

            $presence_data = [
                'user_id'           => $user_id,
                'longitude'         => $longitude,
                'latitude'          => $latitude,
                'location_details'   => 'Rumah ' . $pegawai->full_name,
                'status'            => 'ok',
                'presence_type'     => '2',
                'presence_status'   => 'in_home',
                'date_presence'     => date('Y-m-d'),
                'time'              => $waktu_sekarang,
            ];

        }

        $simpan = Attendance::create($presence_data);
        if($simpan) {
            return response([
                'status' => true,
                'message' => 'Absensi pulang berhasil disimpan'
            ]);
        } else {
            return response([
                'status' => false,
                'nessage' => 'Absensi pulang gagal disimpan'
            ]);
        }
    }

    public function dashboard_riwayat_absen($id)
    {
        $jml_absen_masuk = Attendance::where([
            ['user_id', $id],
            ['presence_type', 1]
        ])->count();

        $jml_absen_pulang = Attendance::where([
            ['user_id', $id],
            ['presence_type', 2]
        ])->count();

        $jml_izin = Permit::where('user_id', $id)->count();

        return response([
            'jumlah_absen_masuk' => $jml_absen_masuk,
            'jumlah_absen_pulang' => $jml_absen_pulang,
            'jumlah_izin' => $jml_izin,
        ]);

    }

    public function riwayat_absen(Request $request)
    {
        $user_id = $request->user_id;
        $start  = $request->input('start', 0);
        $length = $request->input('length', 10);
        $draw   = $request->input('draw');
        $search = $request->input('search.value');

        $datariwayat = Attendance::where('user_id', $user_id)->orderBy('id', 'desc');
        if (!empty($search)) {
            $datariwayat = $datariwayat->where(function ($query) use ($search) {
                $query->where('date_presence', 'like', "%$search%");
            });
        }

        $total = $datariwayat->count();

        if($total == 0) {
            $result['recordsTotal']     = 0;
            $result['recordsFiltered']  = 0;
            $result['draw']             = $draw;
            $result['data']             = [];
            $result['status']           = false;
            $result['message']          = "Data tidak ditemukan";
            return response($result);
        }

        $data = $datariwayat->offset($start)->limit($length)->get();

        $result['recordsTotal']     = $total;
        $result['recordsFiltered']  = $total;
        $result['draw']             = $draw;
        $result['data']             = $data;
        $result['status']           = true;
        $result['message']          = "OK";

        return response($result);
    }

}
