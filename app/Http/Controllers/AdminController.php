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

}
