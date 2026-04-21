<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    // Tampilkan daftar log aktivitas user dengan filter
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user')->orderBy('waktu', 'desc');

        // Filter berdasarkan jenis aksi (login, logout, dll)
        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('waktu', $request->tanggal);
        }

        // Filter berdasarkan nama user
        if ($request->filled('user')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->user . '%');
            });
        }

        $logs = $query->paginate(20);

        // Ambil daftar aksi untuk dropdown filter
        $aksiList = LogAktivitas::distinct()->pluck('aksi');

        return view('admin.log-aktivitas.index', compact('logs', 'aksiList'));
    }
}