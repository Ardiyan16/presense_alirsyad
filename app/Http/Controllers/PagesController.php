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

}
