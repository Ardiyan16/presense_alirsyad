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

        if($cek_izin) {
            return response([
                'status' => false,
                'info' => true,
                'message' => 'Anda sudah melakukan izin, tidak dapat melakukan absensi'
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

    }

}
