<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar\Jurusan;
use App\Models\Pendaftar\Gelombang;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    // Tampilkan halaman kelola jurusan
    public function jurusan()
    {
        $jurusan = Jurusan::all();
        return view('admin.master.jurusan', compact('jurusan'));
    }

    // Tambah jurusan baru
    public function storeJurusan(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:jurusan',
            'nama' => 'required|string|max:100',
            'kuota' => 'required|integer|min:1'
        ]);

        Jurusan::create($request->only(['kode', 'nama', 'kuota']));
        return back()->with('success', 'Jurusan berhasil ditambahkan');
    }

    // Update data jurusan
    public function updateJurusan(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:jurusan,kode,' . $id,
            'nama' => 'required|string|max:100',
            'kuota' => 'required|integer|min:1'
        ]);

        $jurusan = Jurusan::findOrFail($id);
        $jurusan->update($request->only(['kode', 'nama', 'kuota']));
        return back()->with('success', 'Jurusan berhasil diupdate');
    }

    // Hapus jurusan
    public function deleteJurusan($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();
        return back()->with('success', 'Jurusan berhasil dihapus');
    }

    // Tampilkan halaman kelola gelombang pendaftaran
    public function gelombang()
    {
        $gelombang = Gelombang::all();
        return view('admin.master.gelombang', compact('gelombang'));
    }

    // Tambah gelombang baru
    public function storeGelombang(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after:tgl_mulai',
            'biaya_daftar' => 'required|numeric|min:0',
            'tahun' => 'required|integer|min:2020|max:2030'
        ]);

        Gelombang::create($request->all());
        return back()->with('success', 'Gelombang berhasil ditambahkan');
    }

    // Update data gelombang
    public function updateGelombang(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after:tgl_mulai',
            'biaya_daftar' => 'required|numeric|min:0',
            'tahun' => 'required|integer|min:2020|max:2030'
        ]);

        $gelombang = Gelombang::findOrFail($id);
        $gelombang->update($request->all());
        return back()->with('success', 'Gelombang berhasil diupdate');
    }

    // Hapus gelombang (gagal kalau sudah ada pendaftar)
    public function deleteGelombang($id)
    {
        $gelombang = Gelombang::findOrFail($id);
        
        // Cek apakah gelombang masih dipakai pendaftar
        if ($gelombang->pendaftar()->count() > 0) {
            return back()->with('error', 'Gelombang tidak dapat dihapus karena sudah ada pendaftar');
        }
        
        $gelombang->delete();
        return back()->with('success', 'Gelombang berhasil dihapus');
    }

    // Aktifkan/nonaktifkan gelombang (hanya 1 yang boleh aktif)
    public function toggleStatusGelombang($id)
    {
        $gelombang = Gelombang::findOrFail($id);
        
        if ($gelombang->status === 'nonaktif') {
            // Nonaktifkan semua gelombang lain dulu
            Gelombang::where('id', '!=', $id)->update(['status' => 'nonaktif']);
            $gelombang->status = 'aktif';
            $status = 'diaktifkan';
        } else {
            $gelombang->status = 'nonaktif';
            $status = 'dinonaktifkan';
        }
        
        $gelombang->save();
        return back()->with('success', "Gelombang {$gelombang->nama} berhasil {$status}");
    }
}