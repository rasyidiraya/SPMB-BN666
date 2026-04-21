<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar\Pendaftar;
use App\Models\Pendaftar\Jurusan;
use App\Models\Pendaftar\Gelombang;


class LaporanController extends Controller
{
    // Tampilkan halaman laporan keuangan dengan filter
    public function index()
    {
        $jurusan = Jurusan::all();
        $gelombang = Gelombang::all();
        return view('keuangan.laporan.index', compact('jurusan', 'gelombang'));
    }

    // Export data laporan keuangan ke file CSV
    public function export(Request $request)
    {
        // Ambil data pendaftar yang terkait pembayaran
        $query = Pendaftar::with(['jurusan', 'gelombang', 'dataSiswa', 'berkas' => function($q) {
                $q->where('jenis', 'BUKTI_BAYAR');
            }])
            ->whereIn('status', ['ADM_PASS', 'PAYMENT_PENDING', 'PAID', 'PAYMENT_REJECT']);
        
        // Filter berdasarkan jurusan
        if ($request->jurusan_id) {
            $query->where('jurusan_id', $request->jurusan_id);
        }
        
        // Filter berdasarkan gelombang
        if ($request->gelombang_id) {
            $query->where('gelombang_id', $request->gelombang_id);
        }
        
        // Filter berdasarkan status
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan rentang tanggal
        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('created_at', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }
        
        $data = $query->get();
        
        // Buat file CSV untuk didownload
        $filename = 'laporan_keuangan_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Tambah BOM supaya UTF-8 kebaca di Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header CSV
            fputcsv($file, [
                'No Pendaftaran',
                'Nama',
                'Email',
                'Jurusan', 
                'Gelombang',
                'Biaya Daftar',
                'Status Pembayaran',
                'Tanggal Daftar',
                'Tgl Bayar'
            ]);
            
            // Isi data CSV
            foreach ($data as $pendaftar) {
                $buktiBayar = $pendaftar->berkas->first();
                $tglBayar = $buktiBayar && $buktiBayar->tanggal_pembayaran ? 
                    date('Y-m-d', strtotime($buktiBayar->tanggal_pembayaran)) : '-';
                
                fputcsv($file, [
                    $pendaftar->no_pendaftaran,
                    $pendaftar->dataSiswa->nama ?? '-',
                    $pendaftar->email ?? '-',
                    $pendaftar->jurusan->nama ?? '-',
                    $pendaftar->gelombang->nama ?? '-',
                    'Rp ' . number_format($pendaftar->gelombang->biaya_daftar ?? 0),
                    $pendaftar->status,
                    $pendaftar->created_at->format('Y-m-d H:i:s'),
                    $tglBayar
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}