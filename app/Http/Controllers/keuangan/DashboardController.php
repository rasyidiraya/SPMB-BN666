<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Tampilkan dashboard keuangan dengan ringkasan pembayaran
    public function index()
    {
        // Hitung total semua pendaftar
        $totalPendaftar = DB::table('pendaftar')->count();

        // Hitung yang menunggu verifikasi pembayaran
        $menungguVerifikasi = DB::table('pendaftar')->whereIn('status', ['ADM_PASS', 'PAYMENT_PENDING'])->count();

        // Hitung yang sudah bayar
        $sudahBayar = DB::table('pendaftar')->where('status', 'PAID')->count();
        
        // Hitung total pemasukan dari yang sudah bayar
        $totalPemasukan = DB::table('pendaftar')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->where('pendaftar.status', 'PAID')
            ->sum('gelombang.biaya_daftar') ?? 0;

        return view('keuangan.dashboard', compact(
            'totalPendaftar',
            'menungguVerifikasi', 
            'sudahBayar',
            'totalPemasukan'
        ));
    }
}