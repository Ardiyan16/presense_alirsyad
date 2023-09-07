<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $var['title'] = 'Dashboard';
        return view('admin.dashboard', $var);
    }

    public function data_pegawai()
    {
        $var['title'] = 'Data Pegawai';
        return view('admin.pegawai.data_pegawai', $var);
    }

    public function add_pegawai()
    {
        $var['title'] = 'Tambah Pegawai';
        return view('admin.pegawai.add_pegawai', $var);
    }

    public function edit_pegawai(Request $request)
    {
        $var['title'] = 'Edit Pegawai';
        $var['id_pegawai'] = $request->get('id');
        return view('admin.pegawai.edit_pegawai', $var);
    }

    public function profile_saya()
    {
        $var['title'] = 'Profile Saya';
        return view('admin.my_profile', $var);
    }

    public function data_unit()
    {
        $var['title'] = 'Data Unit';
        return view('admin.unit', $var);
    }

    public function data_cuti()
    {
        $var['title'] = 'Data Cuti';
        return view('admin.cuti.cuti', $var);
    }

    public function tambah_cuti()
    {
        $var['title'] = 'Tambah Cuti Pegawai';
        return view('admin.cuti.add_cuti', $var);
    }

    public function edit_cuti(Request $request)
    {
        $var['title'] = 'Edit Cuti Pegawai';
        $var['id_cuti'] = $request->get('id');
        return view('admin.cuti.edit_cuti', $var);
    }

    public function data_jenis_cuti()
    {
        $var['title'] = 'Data Jenis Cuti';
        return view('admin.cuti.jenis_cuti', $var);
    }

    public function data_presensi()
    {
        $var['title'] = 'Data Absensi Karyawan';
        return view('admin.presensi.presensi', $var);
    }

    public function detail_presensi(Request $request)
    {
        $var['title'] = 'Data Detail Absensi';
        $var['user_id'] = $request->get('user_id');
        return view('admin.presensi.detail', $var);
    }

}
