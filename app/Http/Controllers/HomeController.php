<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar\Gelombang;

class HomeController extends Controller
{
    // Tampilkan halaman utama (landing page) beserta data gelombang pendaftaran
    public function index()
    {
        $gelombang = Gelombang::orderBy('tgl_mulai')->get();
        return view('userend.index', compact('gelombang'));
    }
}
