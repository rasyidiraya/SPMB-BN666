<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KelolaUserController extends Controller
{
    // Tampilkan daftar user internal (admin, verifikator, keuangan, kepsek)
    public function index()
    {
        $users = Pengguna::where('role', '!=', 'pendaftar')->get();
        return view('admin.kelola-user.index', compact('users'));
    }

    // Tampilkan daftar user pendaftar
    public function pendaftar()
    {
        $users = Pengguna::where('role', 'pendaftar')->get();
        return view('admin.kelola-user.pendaftar', compact('users'));
    }

    // Tambah user internal baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pengguna',
            'hp' => 'required|string|max:20',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,verifikator_adm,keuangan,kepsek'
        ]);

        Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'hp' => $request->hp,
            'password_hash' => Hash::make($request->password),
            'role' => $request->role,
            'aktif' => true
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    // Update data user internal
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pengguna,email,' . $id,
            'hp' => 'required|string|max:20',
            'role' => 'required|in:admin,verifikator_adm,keuangan,kepsek'
        ]);

        $user = Pengguna::findOrFail($id);
        $data = $request->only(['nama', 'email', 'hp', 'role']);
        
        // Update password kalau diisi
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password_hash'] = Hash::make($request->password);
        }

        $user->update($data);
        return back()->with('success', 'User berhasil diupdate');
    }

    // Hapus user beserta semua data terkaitnya
    public function destroy($id)
    {
        try {
            $user = Pengguna::findOrFail($id);
            
            // Hapus log aktivitas user
            \App\Models\LogAktivitas::where('user_id', $id)->delete();
            
            // Hapus data pendaftar kalau ada
            $pendaftar = \App\Models\Pendaftar\Pendaftar::where('user_id', $id)->first();
            if ($pendaftar) {
                // Hapus semua data terkait pendaftar
                \App\Models\Pendaftar\PendaftarBerkas::where('pendaftar_id', $pendaftar->id)->delete();
                \App\Models\Pendaftar\PendaftarDataOrtu::where('pendaftar_id', $pendaftar->id)->delete();
                \App\Models\Pendaftar\PendaftarAsalSekolah::where('pendaftar_id', $pendaftar->id)->delete();
                \App\Models\Pendaftar\PendaftarDataSiswa::where('pendaftar_id', $pendaftar->id)->delete();
                $pendaftar->delete();
            }

            $user->delete();
            return back()->with('success', 'User berhasil dihapus beserta data terkaitnya');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus user karena data masih terhubung dengan sistem lain. Coba nonaktifkan user saja.');
        }
    }

    // Aktifkan/nonaktifkan user
    public function toggleStatus($id)
    {
        $user = Pengguna::findOrFail($id);
        
        // Cegah menonaktifkan diri sendiri
        if ($user->id == auth('admin')->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun Anda sendiri');
        }
        
        // Cegah menonaktifkan admin terakhir
        if ($user->role == 'admin' && $user->aktif) {
            $activeAdminCount = Pengguna::where('role', 'admin')->where('aktif', true)->count();
            if ($activeAdminCount <= 1) {
                return back()->with('error', 'Tidak dapat menonaktifkan admin terakhir');
            }
        }
        
        // Toggle status aktif/nonaktif
        $user->aktif = !$user->aktif;
        $user->save();
        
        $status = $user->aktif ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User berhasil {$status}");
    }
}