<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index()
    {
        $var['title'] = 'Presence APPS';
        return view('pages.home', $var);
    }

    public function absen_masuk()
    {
        $var['title'] = 'Absen Masuk';
        return view('pages.absen_masuk', $var);
    }

}
