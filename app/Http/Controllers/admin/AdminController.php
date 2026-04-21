<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar\Pendaftar;
use App\Models\Pendaftar\Jurusan;
use App\Models\Pendaftar\Gelombang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Tampilkan dashboard admin dengan statistik pendaftaran
    public function dashboard(Request $request)
    {
        $today = now()->format('Y-m-d');
        
        $query = Pendaftar::query();
        
        // Filter per jurusan (kalau dipilih)
        if ($request->jurusan_id) {
            $query->where('jurusan_id', $request->jurusan_id);
        }
        
        // Filter per gelombang (kalau dipilih)
        if ($request->gelombang_id) {
            $query->where('gelombang_id', $request->gelombang_id);
        }
        
        // Hitung total pendaftar hari ini
        $pendaftarHariIni = (clone $query)->whereDate('created_at', $today)->count();
        
        // Hitung total terverifikasi hari ini
        $terverifikasiHariIni = (clone $query)->whereDate('tgl_verifikasi_adm', $today)
            ->whereIn('status', ['ADM_PASS', 'PAYMENT_PENDING', 'PAID'])->count();
        
        // Hitung total terbayar hari ini
        $terbayarHariIni = (clone $query)->whereDate('updated_at', $today)
            ->where('status', 'PAID')->count();
        
        // Ambil data statistik per jurusan
        $dataPerJurusan = DB::table('pendaftar')
            ->join('jurusan', 'pendaftar.jurusan_id', '=', 'jurusan.id')
            ->select(
                'jurusan.nama',
                DB::raw('COUNT(*) as total_pendaftar'),
                DB::raw('SUM(CASE WHEN pendaftar.status IN ("ADM_PASS", "PAYMENT_PENDING", "PAID") THEN 1 ELSE 0 END) as terverifikasi'),
                DB::raw('SUM(CASE WHEN pendaftar.status = "PAID" THEN 1 ELSE 0 END) as terbayar')
            )
            ->groupBy('jurusan.id', 'jurusan.nama')
            ->get();
        
        // Ambil data statistik per gelombang
        $dataPerGelombang = DB::table('pendaftar')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->select(
                'gelombang.nama',
                DB::raw('COUNT(*) as total_pendaftar'),
                DB::raw('SUM(CASE WHEN pendaftar.status IN ("ADM_PASS", "PAYMENT_PENDING", "PAID") THEN 1 ELSE 0 END) as terverifikasi'),
                DB::raw('SUM(CASE WHEN pendaftar.status = "PAID" THEN 1 ELSE 0 END) as terbayar')
            )
            ->groupBy('gelombang.id', 'gelombang.nama')
            ->get();
        
        // Data grafik 7 hari terakhir (hanya yang sudah PAID)
        $grafikData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $grafikData[] = [
                'hari' => $date->locale('id')->dayName,
                'pendaftar' => Pendaftar::where('status', 'PAID')
                    ->where(function($query) use ($date) {
                        $query->whereDate('tgl_verifikasi_payment', $date->format('Y-m-d'))
                              ->orWhere(function($q) use ($date) {
                                  $q->whereNull('tgl_verifikasi_payment')
                                    ->whereDate('updated_at', $date->format('Y-m-d'));
                              });
                    })
                    ->count()
            ];
        }
        
        // Ambil data jurusan & gelombang untuk dropdown filter
        $jurusanList = Jurusan::all();
        $gelombangList = Gelombang::all();
        
        return view('admin.dashboard', compact(
            'pendaftarHariIni',
            'terverifikasiHariIni', 
            'terbayarHariIni',
            'dataPerJurusan',
            'dataPerGelombang',
            'grafikData',
            'jurusanList',
            'gelombangList'
        ));
    }
}