<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Permit;
use App\Models\Attendance;

class PagesController extends Controller
{
    public function index()
    {
        $var['title'] = 'Presence APPS';
        return view('pages.home', $var);
    }

    public function absen_masuk(Request $request)
    {
        $var['title'] = 'Absen Masuk';
        $var['jenis_lokasi'] = $request->get('jenis_lokasi');
        return view('pages.absen_masuk', $var);
    }

    public function absen_keluar()
    {
        $var['title'] = 'Absen Keluar';
        return view('pages.absen_keluar', $var);
    }

    public function izin()
    {
        $var['title'] = 'Izin';
        return view('pages.izin', $var);
    }

    public function simpan_izin(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $hari_ini = date('Y-m-d');
        $user_id = $request->user_id;
        $request->validate([
            'type_permit'   => 'required',
            'photo'         => 'image|mimes:jpeg,png,jpg,gif,svg|max:3024',
            'necessity'     => 'required'
        ], [
            'type_permit.required'  => 'Jenis izin wajib diisi',
            'necessity.required'    => 'Alasan wajib diisi'
        ]);

        $cek_absen = Attendance::where([
            ['user_id', '=', $user_id],
            ['date_presence', 'LIKE', "{$hari_ini}%"],
        ])->first();

        $cek_izin = Permit::where([
            ['user_id', '=', $user_id],
            ['date', 'LIKE', "{$hari_ini}%"],
        ])->first();

        if($cek_absen) {
            Alert::info('Info', 'Anda sudah absen hari ini, tidak bisa melakukan izin');
            return redirect('/user/izin');
        }

        if($cek_izin) {
            Alert::info('Info', 'Anda sudah izin hari ini');
            return redirect('/user/izin');
        }

        $file = $request->file('photo');
        $nama_file = $user_id . "-" . time() . "-" . $file->getClientOriginalName();
        $tujuan_upload = 'image_permit';
        $file->move($tujuan_upload, $nama_file);

        $permit = [
            'user_id'       => $user_id,
            'date'          => $hari_ini,
            'time'          => date('H:i:s'),
            'type_permit'   => $request->type_permit,
            'photo'         => $nama_file,
            'necessity'     => $request->necessity
        ];

        $simpan = Permit::create($permit);

        if($simpan) {
            Alert::success('Berhasil', 'Data izin berhasil disimpan');
            return redirect('/user/izin');
        }

        Alert::warning('Gagal', 'Data izin gagal disimpan');
        return redirect('/user/izin');
    }

    public function riwayat_absen()
    {
        $var['title'] = 'Riwayat Absen';
        return view('pages.riwayat', $var);
    }

}
