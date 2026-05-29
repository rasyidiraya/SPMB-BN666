<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('pendaftar')
            ->join('pendaftar_data_siswa', 'pendaftar.id', '=', 'pendaftar_data_siswa.pendaftar_id')
            ->join('jurusan', 'pendaftar.jurusan_id', '=', 'jurusan.id')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->select(
                'pendaftar.*',
                'pendaftar_data_siswa.nama',
                'jurusan.nama as nama_jurusan',
                'gelombang.nama as nama_gelombang'
            );

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('pendaftar.status', $request->status);
        }

        // Filter berdasarkan jurusan
        if ($request->filled('jurusan_id')) {
            $query->where('pendaftar.jurusan_id', $request->jurusan_id);
        }

        // Filter berdasarkan gelombang
        if ($request->filled('gelombang_id')) {
            $query->where('pendaftar.gelombang_id', $request->gelombang_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->where('pendaftar.tanggal_daftar', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->where('pendaftar.tanggal_daftar', '<=', $request->tanggal_sampai);
        }

        $pendaftar = $query->orderBy('pendaftar.created_at', 'desc')->get();

        // Statistik
        $statistik = [
            'total' => $pendaftar->count(),
            'draft' => $pendaftar->where('status', 'DRAFT')->count(),
            'submit' => $pendaftar->where('status', 'SUBMIT')->count(),
            'adm_accept' => $pendaftar->where('status', 'ADM_ACCEPT')->count(),
            'adm_reject' => $pendaftar->where('status', 'ADM_REJECT')->count(),
            'payment_pending' => $pendaftar->where('status', 'PAYMENT_PENDING')->count(),
            'payment_accept' => $pendaftar->where('status', 'PAYMENT_ACCEPT')->count(),
            'payment_reject' => $pendaftar->where('status', 'PAYMENT_REJECT')->count(),
        ];

        // Data per jurusan
        $perJurusan = DB::table('pendaftar')
            ->join('jurusan', 'pendaftar.jurusan_id', '=', 'jurusan.id')
            ->select('jurusan.nama', DB::raw('count(*) as total'))
            ->groupBy('jurusan.id', 'jurusan.nama')
            ->get();

        // Data per gelombang
        $perGelombang = DB::table('pendaftar')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->select('gelombang.nama', DB::raw('count(*) as total'))
            ->groupBy('gelombang.id', 'gelombang.nama')
            ->get();

        $jurusan = DB::table('jurusan')->get();
        $gelombang = DB::table('gelombang')->get();

        return view('kepsek.laporan', compact('pendaftar', 'statistik', 'perJurusan', 'perGelombang', 'jurusan', 'gelombang'));
    }

    public function export(Request $request)
    {
        $query = DB::table('pendaftar')
            ->join('pendaftar_data_siswa', 'pendaftar.id', '=', 'pendaftar_data_siswa.pendaftar_id')
            ->join('jurusan', 'pendaftar.jurusan_id', '=', 'jurusan.id')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->select(
                'pendaftar.no_pendaftaran',
                'pendaftar_data_siswa.nama',
                'jurusan.nama as nama_jurusan',
                'gelombang.nama as nama_gelombang',
                'pendaftar.tanggal_daftar',
                'pendaftar.status'
            );

        if ($request->filled('status')) {
            $query->where('pendaftar.status', $request->status);
        }
        if ($request->filled('jurusan_id')) {
            $query->where('pendaftar.jurusan_id', $request->jurusan_id);
        }
        if ($request->filled('gelombang_id')) {
            $query->where('pendaftar.gelombang_id', $request->gelombang_id);
        }
        if ($request->filled('tanggal_dari')) {
            $query->where('pendaftar.tanggal_daftar', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('pendaftar.tanggal_daftar', '<=', $request->tanggal_sampai);
        }

        $data = $query->get();

        $filename = 'laporan_pendaftar_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No. Pendaftaran', 'Nama', 'Jurusan', 'Gelombang', 'Tanggal Daftar', 'Status']);

            foreach ($data as $row) {
                fputcsv($file, [
                    $row->no_pendaftaran,
                    $row->nama,
                    $row->nama_jurusan,
                    $row->nama_gelombang,
                    $row->tanggal_daftar,
                    $row->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
