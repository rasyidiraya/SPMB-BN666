<?php

namespace App\Http\Controllers\VerifikatorAdministrasi;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar\Pendaftar;
use App\Models\Pendaftar\PendaftarBerkas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikatorController extends Controller
{

    // Tampilkan daftar pendaftar yang menunggu verifikasi administrasi
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255'
        ]);
        
        // Ambil data pendaftar berstatus SUBMIT
        $query = DB::table('pendaftar')
            ->join('pendaftar_data_siswa', 'pendaftar.id', '=', 'pendaftar_data_siswa.pendaftar_id')
            ->join('jurusan', 'pendaftar.jurusan_id', '=', 'jurusan.id')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->select(
                'pendaftar.id',
                'pendaftar.no_pendaftaran',
                'pendaftar_data_siswa.nama',
                'jurusan.nama as nama_jurusan',
                'gelombang.nama as nama_gelombang',
                'pendaftar.status',
                'pendaftar.created_at'
            )
            ->whereIn('pendaftar.status', ['SUBMIT'])
            ->orderBy('pendaftar.created_at', 'desc');

        // Filter pencarian berdasarkan no pendaftaran atau nama
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('pendaftar.no_pendaftaran', 'like', $searchTerm)
                  ->orWhere('pendaftar_data_siswa.nama', 'like', $searchTerm);
            });
        }

        $pendaftar = $query->paginate(20);
        
        return view('verifikator-administrasi.index', compact('pendaftar'));
    }

    // Tampilkan detail data pendaftar beserta berkas yang diupload
    public function detail($id)
    {
        if (!is_numeric($id)) {
            abort(404);
        }
        
        // Ambil semua data pendaftar (siswa, ortu, asal sekolah, jurusan, gelombang)
        $pendaftar = DB::table('pendaftar')
            ->leftJoin('pendaftar_data_siswa', 'pendaftar.id', '=', 'pendaftar_data_siswa.pendaftar_id')
            ->leftJoin('pendaftar_data_ortu', 'pendaftar.id', '=', 'pendaftar_data_ortu.pendaftar_id')
            ->leftJoin('pendaftar_asal_sekolah', 'pendaftar.id', '=', 'pendaftar_asal_sekolah.pendaftar_id')
            ->leftJoin('jurusan', 'pendaftar.jurusan_id', '=', 'jurusan.id')
            ->leftJoin('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->select('pendaftar.*', 'pendaftar_data_siswa.*', 'pendaftar_data_ortu.*', 'pendaftar_asal_sekolah.*', 'jurusan.nama as nama_jurusan', 'gelombang.nama as nama_gelombang')
            ->where('pendaftar.id', (int)$id)
            ->first();

        if (!$pendaftar) {
            $exists = DB::table('pendaftar')->where('id', (int)$id)->exists();
            if ($exists) {
                abort(500, 'Data pendaftar ada tapi join gagal');
            } else {
                abort(404, 'Data pendaftar dengan ID ' . $id . ' tidak ditemukan');
            }
        }

        // Ambil daftar berkas yang diupload
        $berkas = DB::table('pendaftar_berkas')
            ->where('pendaftar_id', $id)
            ->select('id', 'jenis', 'nama_file', 'url', 'ukuran_kb', 'valid')
            ->get();

        return view('verifikator-administrasi.detail', compact('pendaftar', 'berkas'));
    }

    // Proses verifikasi administrasi (terima/tolak)
    public function verifikasi(Request $request, $id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return back()->withErrors(['error' => 'ID tidak valid']);
        }
        
        $id = (int)$id;
        
        $request->validate([
            'status' => 'required|in:ADM_PASS,ADM_REJECT',
            'catatan' => 'nullable|string|max:500'
        ]);

        // Update status pendaftar
        DB::table('pendaftar')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'catatan' => $request->catatan,
                'tgl_verifikasi_adm' => now()
            ]);

        return redirect()->route('verifikator.index')
            ->with('success', 'Verifikasi berhasil disimpan');
    }

    // Validasi berkas individual (tandai valid/tidak valid)
    public function verifikasiBerkas(Request $request, $berkasId)
    {
        $request->validate([
            'valid' => 'required|boolean'
        ]);

        DB::table('pendaftar_berkas')
            ->where('id', $berkasId)
            ->update([
                'valid' => $request->valid,
                'updated_at' => now()
            ]);

        return back()->with('success', 'Status berkas berhasil diperbarui');
    }

    // Tampilkan riwayat pendaftar yang sudah diverifikasi
    public function riwayat(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255'
        ]);
        
        // Ambil data pendaftar yang sudah diverifikasi
        $query = DB::table('pendaftar')
            ->join('pendaftar_data_siswa', 'pendaftar.id', '=', 'pendaftar_data_siswa.pendaftar_id')
            ->join('jurusan', 'pendaftar.jurusan_id', '=', 'jurusan.id')
            ->join('gelombang', 'pendaftar.gelombang_id', '=', 'gelombang.id')
            ->select(
                'pendaftar.id',
                'pendaftar.no_pendaftaran',
                'pendaftar_data_siswa.nama',
                'jurusan.nama as nama_jurusan',
                'gelombang.nama as nama_gelombang',
                'pendaftar.status',
                'pendaftar.tgl_verifikasi_adm'
            )
            ->whereIn('pendaftar.status', ['ADM_PASS', 'ADM_REJECT', 'PAYMENT_PENDING', 'PAID'])
            ->whereNotNull('pendaftar.tgl_verifikasi_adm')
            ->orderBy('pendaftar.tgl_verifikasi_adm', 'desc');

        // Filter pencarian
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('pendaftar.no_pendaftaran', 'like', $searchTerm)
                  ->orWhere('pendaftar_data_siswa.nama', 'like', $searchTerm);
            });
        }

        $pendaftar = $query->paginate(20);
        
        return view('verifikator-administrasi.riwayat', compact('pendaftar'));
    }
}