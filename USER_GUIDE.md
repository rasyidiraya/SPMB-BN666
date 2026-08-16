# USER GUIDE - SISTEM PENERIMAAN MAHASISWA BARU (SPMB)

## DAFTAR ISI
1. [Pendahuluan](#pendahuluan)
2. [Akses Sistem](#akses-sistem)
3. [Panduan untuk Pendaftar](#panduan-untuk-pendaftar)
4. [Panduan untuk Admin](#panduan-untuk-admin)
5. [Panduan untuk Verifikator Administrasi](#panduan-untuk-verifikator-administrasi)
6. [Panduan untuk Keuangan](#panduan-untuk-keuangan)
7. [Panduan untuk Kepala Sekolah](#panduan-untuk-kepala-sekolah)
8. [FAQ](#faq)

---

## PENDAHULUAN

Sistem Penerimaan Mahasiswa Baru (SPMB) adalah aplikasi berbasis web untuk mengelola proses pendaftaran mahasiswa baru secara online. Sistem ini memiliki 5 jenis pengguna dengan hak akses berbeda:

- **Pendaftar**: Calon mahasiswa yang mendaftar
- **Admin**: Mengelola master data dan monitoring
- **Verifikator Administrasi**: Memverifikasi berkas pendaftar
- **Keuangan**: Memverifikasi pembayaran
- **Kepala Sekolah**: Melihat laporan dan dashboard

---

## AKSES SISTEM

### URL Akses
- **Lokal (XAMPP)**: `http://localhost/rasid/spmb_bn_bY_rasit/public`
- **Development Server**: `http://127.0.0.1:8000`

### Persyaratan Sistem
- Browser: Chrome, Firefox, Edge (versi terbaru)
- Koneksi Internet
- Email aktif untuk registrasi

---

## PANDUAN UNTUK PENDAFTAR

### 1. REGISTRASI AKUN

![Screenshot: Halaman Utama dengan tombol Daftar]

#### Langkah-langkah:
1. Buka halaman utama website
2. Klik tombol **"Daftar"** atau **"Registrasi"**

![Screenshot: Form Registrasi]
3. Isi form registrasi:
   - Nama Lengkap
   - Email (gunakan email aktif)
   - Nomor HP/WhatsApp
   - Password (minimal 6 karakter)
   - Konfirmasi Password
4. Klik tombol **"Daftar"**
5. Sistem akan mengirim OTP ke email Anda

![Screenshot: Halaman Input OTP]

6. Masukkan kode OTP yang diterima
7. Klik **"Verifikasi"**
8. Akun berhasil dibuat

![Screenshot: Notifikasi Registrasi Berhasil]

> **Catatan**: Simpan email dan password Anda dengan baik!

---

### 2. LOGIN

![Screenshot: Halaman Login]

#### Langkah-langkah:
1. Buka halaman login
2. Masukkan **Email** dan **Password**
3. Klik tombol **"Login"**
4. Anda akan diarahkan ke Dashboard Pendaftar

![Screenshot: Dashboard Pendaftar]

---

### 3. MENGISI FORMULIR PENDAFTARAN

![Screenshot: Menu Pendaftaran di Sidebar]

#### Langkah-langkah:
1. Setelah login, klik menu **"Pendaftaran"**
2. Isi form dengan lengkap dan benar:

![Screenshot: Form Pendaftaran - Bagian Data Pribadi]

   **A. Data Pribadi:**
   - Nama Lengkap (sesuai ijazah)
   - NIK (16 digit)
   - NISN
   - Tempat Lahir
   - Tanggal Lahir
   - Jenis Kelamin
   - Agama
   - Alamat Lengkap
   - Pilih Wilayah (Provinsi, Kabupaten, Kecamatan, Kelurahan)

![Screenshot: Form Pendaftaran - Bagian Data Orang Tua]

   **B. Data Orang Tua:**
   - Nama Ayah
   - Nama Ibu
   - Pekerjaan Ayah
   - Pekerjaan Ibu
   - No. HP Ayah
   - No. HP Ibu

![Screenshot: Form Pendaftaran - Bagian Data Asal Sekolah]

   **C. Data Asal Sekolah:**
   - Nama Sekolah
   - NPSN
   - Alamat Sekolah
   - Kabupaten/Kota

![Screenshot: Form Pendaftaran - Pilihan Jurusan dan Gelombang]

   **D. Pilihan Jurusan dan Gelombang:**
   - Pilih Jurusan yang diminati
   - Pilih Gelombang Pendaftaran yang aktif

3. Periksa kembali semua data
4. Klik tombol **"Simpan"**
5. Anda akan diarahkan ke halaman Upload Berkas

![Screenshot: Notifikasi Data Berhasil Disimpan]

> **Penting**: Pastikan semua data yang diisi benar dan sesuai dokumen asli!

---

### 4. UPLOAD BERKAS PERSYARATAN

![Screenshot: Halaman Upload Berkas]

#### Berkas yang Harus Diupload:
1. **Ijazah/Surat Keterangan Lulus** (Wajib)
2. **Akta Kelahiran** (Wajib)
3. **Kartu Keluarga (KK)** (Wajib)
4. **Kartu Indonesia Pintar (KIP)** (Opsional)

#### Ketentuan File:
- Format: PDF, JPG, JPEG, atau PNG
- Ukuran maksimal: 5 MB per file
- File harus jelas dan terbaca

#### Langkah-langkah:
1. Klik menu **"Upload Berkas"**
2. Klik tombol **"Pilih File"** untuk setiap jenis berkas
3. Pilih file dari komputer Anda
4. Pastikan semua berkas wajib sudah dipilih
5. Klik tombol **"Upload"**
6. Tunggu proses upload selesai

![Screenshot: Progress Upload Berkas]

7. Status berubah menjadi **"SUBMIT"** (Menunggu Verifikasi)

![Screenshot: Notifikasi Upload Berhasil]

> **Tips**: Scan dokumen dengan resolusi yang baik agar mudah dibaca

---

### 5. MELAKUKAN PEMBAYARAN

![Screenshot: Menu Pembayaran]

#### Langkah-langkah:
1. Tunggu hingga berkas Anda diverifikasi oleh admin
2. Jika berkas **DITERIMA**, klik menu **"Pembayaran"**
3. Lihat informasi biaya pendaftaran

![Screenshot: Halaman Pembayaran dengan Info Biaya dan Rekening]
4. Lakukan pembayaran sesuai metode yang tersedia:
   - Transfer Bank
   - Tunai ke kasir
5. Setelah membayar, upload bukti pembayaran:
   - Klik **"Upload Bukti Pembayaran"**

![Screenshot: Form Upload Bukti Pembayaran]
   - Pilih file bukti transfer/struk
   - Isi tanggal pembayaran
   - Pilih metode pembayaran
   - Masukkan nominal yang dibayar
   - Klik **"Upload"**
6. Status berubah menjadi **"PAYMENT_PENDING"**

![Screenshot: Notifikasi Bukti Pembayaran Berhasil Diupload]

#### Informasi Rekening:
(Sesuaikan dengan rekening sekolah Anda)
- Bank: BNI
- No. Rekening: 1234567890
- Atas Nama: Sekolah XYZ

---

### 6. CEK STATUS PENDAFTARAN

![Screenshot: Menu Status]

#### Langkah-langkah:
1. Klik menu **"Status"**
2. Lihat status pendaftaran Anda:

![Screenshot: Halaman Status Pendaftaran dengan Badge Status]
   - **DRAFT**: Belum submit
   - **SUBMIT**: Menunggu verifikasi berkas
   - **ADM_ACCEPT**: Berkas diterima, lanjut pembayaran
   - **ADM_REJECT**: Berkas ditolak, perbaiki dan upload ulang
   - **PAYMENT_PENDING**: Menunggu verifikasi pembayaran
   - **PAYMENT_ACCEPT**: Pembayaran diterima, pendaftaran selesai
   - **PAYMENT_REJECT**: Pembayaran ditolak, upload ulang bukti

3. Jika ada catatan dari verifikator, baca dengan teliti
4. Lakukan perbaikan jika diminta

---

### 7. CETAK KARTU PENDAFTARAN

![Screenshot: Menu Cetak Kartu]

#### Langkah-langkah:
1. Tunggu hingga status **"PAYMENT_ACCEPT"**
2. Klik menu **"Cetak Kartu"**
3. Kartu pendaftaran akan ditampilkan

![Screenshot: Preview Kartu Pendaftaran]
4. Klik tombol **"Print"** atau **"Download PDF"**
5. Simpan dan cetak kartu
6. Bawa kartu saat ujian/tes masuk

---

### 8. LOGOUT

#### Langkah-langkah:
1. Klik menu **"Logout"** di sidebar
2. Anda akan keluar dari sistem
3. Untuk login kembali, gunakan email dan password yang sama

---

## PANDUAN UNTUK ADMIN

### 1. LOGIN ADMIN

![Screenshot: Halaman Login Admin]

#### Langkah-langkah:
1. Buka halaman login
2. Masukkan email dan password admin
3. Klik **"Login"**
4. Anda akan masuk ke Dashboard Admin

![Screenshot: Dashboard Admin dengan Statistik]

---

### 2. MENGELOLA MASTER JURUSAN

![Screenshot: Halaman Master Jurusan dengan Tabel Data]

#### Menambah Jurusan:
1. Klik menu **"Master Jurusan"**
2. Klik tombol **"Tambah Jurusan"**

![Screenshot: Modal Form Tambah Jurusan]
3. Isi form:
   - Kode Jurusan (contoh: TKJ)
   - Nama Jurusan (contoh: Teknik Komputer Jaringan)
   - Kuota (jumlah siswa yang diterima)
4. Klik **"Simpan"**

#### Mengedit Jurusan:
1. Klik tombol **Edit** (icon pensil) pada jurusan yang ingin diubah
2. Ubah data yang diperlukan
3. Klik **"Update"**

#### Menghapus Jurusan:
1. Klik tombol **Hapus** (icon tempat sampah)
2. Konfirmasi penghapusan
3. Klik **"Ya"**

> **Perhatian**: Jurusan yang sudah digunakan tidak dapat dihapus

---

### 3. MENGELOLA GELOMBANG PENDAFTARAN

![Screenshot: Halaman Master Gelombang dengan Tabel Data]

#### Menambah Gelombang:
1. Klik menu **"Master Gelombang"**
2. Klik tombol **"Tambah Gelombang"**

![Screenshot: Modal Form Tambah Gelombang]
3. Isi form:
   - Nama Gelombang (contoh: Gelombang 1)
   - Tanggal Mulai
   - Tanggal Selesai
   - Biaya Pendaftaran (dalam Rupiah)
   - Tahun Ajaran
4. Klik **"Simpan"**

#### Mengaktifkan/Menonaktifkan Gelombang:
1. Klik tombol **"Aktifkan"** atau **"Nonaktifkan"**
2. Hanya 1 gelombang yang bisa aktif dalam satu waktu
3. Gelombang aktif akan muncul di form pendaftaran

#### Mengedit Gelombang:
1. Klik tombol **Edit** (icon pensil)
2. Ubah data yang diperlukan
3. Klik **"Update"**

#### Menghapus Gelombang:
1. Klik tombol **Hapus** (icon tempat sampah)
2. Konfirmasi penghapusan
3. Klik **"Ya"**

---

### 4. MENGELOLA USER

![Screenshot: Halaman Kelola User dengan Tabel Data]

#### Menambah User:
1. Klik menu **"Kelola User"**
2. Klik tombol **"Tambah User"**

![Screenshot: Modal Form Tambah User]
3. Isi form:
   - Nama
   - Email
   - Nomor HP
   - Password
   - Role (Admin/Verifikator/Keuangan/Kepala Sekolah)
4. Klik **"Simpan"**

#### Mengedit User:
1. Klik tombol **Edit** (icon pensil)
2. Ubah data yang diperlukan
3. Password bisa dikosongkan jika tidak ingin diubah
4. Klik **"Update"**

#### Menonaktifkan User:
1. Klik tombol **"Nonaktifkan"**
2. User tidak bisa login saat status nonaktif
3. Klik **"Aktifkan"** untuk mengaktifkan kembali

#### Menghapus User:
1. Klik tombol **Hapus** (icon tempat sampah)
2. Konfirmasi penghapusan
3. Klik **"Ya"**

---

### 5. MONITORING BERKAS

![Screenshot: Halaman Monitoring Berkas dengan Filter]

#### Langkah-langkah:
1. Klik menu **"Monitoring Berkas"**
2. Lihat daftar semua pendaftar

![Screenshot: Tabel Data Pendaftar dengan Status]
3. Filter berdasarkan:
   - Status
   - Jurusan
   - Gelombang
4. Klik **"Detail"** untuk melihat data lengkap pendaftar
5. Klik **"Export"** untuk download data ke Excel

---

### 6. MELIHAT PETA SEBARAN

![Screenshot: Halaman Peta Sebaran dengan Map]

#### Langkah-langkah:
1. Klik menu **"Peta Sebaran"**
2. Lihat peta sebaran pendaftar berdasarkan wilayah

![Screenshot: Detail Marker di Peta]
3. Klik marker di peta untuk melihat detail
4. Gunakan untuk analisis demografi pendaftar

---

### 7. MEMBUAT LAPORAN

![Screenshot: Halaman Laporan dengan Filter]

#### Langkah-langkah:
1. Klik menu **"Laporan"**
2. Pilih jenis laporan:
   - Laporan Pendaftar
   - Laporan per Jurusan
   - Laporan per Gelombang
3. Pilih periode/filter
4. Klik **"Generate Laporan"**
5. Klik **"Export"** untuk download

---

### 8. MELIHAT LOG AKTIVITAS

![Screenshot: Halaman Log Aktivitas dengan Tabel]

#### Langkah-langkah:
1. Klik menu **"Log Aktivitas"**
2. Lihat semua aktivitas user di sistem

![Screenshot: Detail Log dengan Filter]
3. Filter berdasarkan:
   - User
   - Tanggal
   - Jenis aktivitas
4. Gunakan untuk audit dan monitoring

---

## PANDUAN UNTUK VERIFIKATOR ADMINISTRASI

### 1. LOGIN VERIFIKATOR

![Screenshot: Dashboard Verifikator]

#### Langkah-langkah:
1. Buka halaman login
2. Masukkan email dan password verifikator
3. Klik **"Login"**
4. Anda akan masuk ke Dashboard Verifikator

---

### 2. MEMVERIFIKASI BERKAS PENDAFTAR

![Screenshot: Halaman Verifikasi Berkas dengan Daftar Pendaftar]

#### Langkah-langkah:
1. Klik menu **"Verifikasi Berkas"**
2. Lihat daftar pendaftar dengan status **"SUBMIT"**
3. Klik tombol **"Detail"** pada pendaftar yang akan diverifikasi

![Screenshot: Halaman Detail Pendaftar dengan Preview Berkas]

4. Periksa data pendaftar:
   - Data Pribadi
   - Data Orang Tua
   - Data Asal Sekolah
5. Periksa berkas yang diupload:
   - Klik file untuk melihat/download

![Screenshot: Preview File Berkas (Ijazah, Akta, KK)]

   - Pastikan file jelas dan sesuai
6. Verifikasi setiap berkas:
   - Centang **"Valid"** jika berkas sesuai
   - Isi **"Catatan"** jika ada yang perlu diperbaiki

![Screenshot: Form Verifikasi dengan Checkbox Valid dan Input Catatan]

7. Pilih keputusan:
   - **"Terima"**: Jika semua berkas valid
   - **"Tolak"**: Jika ada berkas yang tidak sesuai
8. Isi catatan untuk pendaftar (wajib jika ditolak)
9. Klik **"Simpan Verifikasi"**

![Screenshot: Notifikasi Verifikasi Berhasil]ada pendaftar yang akan diverifikasi
4. Periksa data pendaftar:
   - Data Pribadi
   - Data Orang Tua
   - Data Asal Sekolah
5. Periksa berkas yang diupload:
   - Klik file untuk melihat/download
   - Pastikan file jelas dan sesuai
6. Verifikasi setiap berkas:
   - Centang **"Valid"** jika berkas sesuai
   - Isi **"Catatan"** jika ada yang perlu diperbaiki
7. Pilih keputusan:
   - **"Terima"**: Jika semua berkas valid
   - **"Tolak"**: Jika ada berkas yang tidak sesuai
8. Isi catatan untuk pendaftar (wajib jika ditolak)
9. Klik **"Simpan Verifikasi"**

#### Tips Verifikasi:
- Periksa kesesuaian nama di semua dokumen
- Pastikan foto/scan jelas dan terbaca
- Berikan catatan yang jelas jika menolak
- Verifikasi NIK dan NISN

---

### 3. MELIHAT RIWAYAT VERIFIKASI

![Screenshot: Halaman Riwayat Verifikasi]

#### Langkah-langkah:
1. Klik menu **"Riwayat"**
2. Lihat semua pendaftar yang sudah Anda verifikasi
3. Filter berdasarkan:
   - Status (Diterima/Ditolak)
   - Tanggal
   - Jurusan
4. Klik **"Detail"** untuk melihat kembali

---

### 4. MEMBUAT LAPORAN VERIFIKASI

![Screenshot: Halaman Laporan Verifikator]

#### Langkah-langkah:
1. Klik menu **"Laporan"**
2. Pilih periode laporan
3. Klik **"Generate"**
4. Klik **"Export"** untuk download Excel

---

## PANDUAN UNTUK KEUANGAN

### 1. LOGIN KEUANGAN

![Screenshot: Dashboard Keuangan dengan Statistik Pembayaran]

#### Langkah-langkah:
1. Buka halaman login
2. Masukkan email dan password keuangan
3. Klik **"Login"**
4. Anda akan masuk ke Dashboard Keuangan

---

### 2. MEMVERIFIKASI PEMBAYARAN

![Screenshot: Halaman Verifikasi Pembayaran dengan Daftar]

#### Langkah-langkah:
1. Klik menu **"Verifikasi Pembayaran"**
2. Lihat daftar pendaftar dengan status **"PAYMENT_PENDING"**
3. Klik tombol **"Detail"** pada pendaftar

![Screenshot: Detail Pembayaran dengan Preview Bukti Transfer]
4. Periksa bukti pembayaran:
   - Klik file untuk melihat bukti transfer/struk
   - Periksa tanggal pembayaran
   - Periksa nominal yang dibayar
   - Periksa metode pembayaran
5. Cocokkan dengan data di rekening/kasir
6. Pilih keputusan:
   - **"Terima"**: Jika pembayaran sesuai
   - **"Tolak"**: Jika pembayaran tidak sesuai/tidak ditemukan

![Screenshot: Form Verifikasi Pembayaran dengan Tombol Terima/Tolak]

7. Isi catatan (wajib jika ditolak)
8. Klik **"Simpan Verifikasi"**

![Screenshot: Notifikasi Verifikasi Pembayaran Berhasil]

#### Tips Verifikasi Pembayaran:
- Cocokkan nominal dengan biaya gelombang
- Periksa tanggal transfer
- Pastikan nama pengirim sesuai
- Berikan catatan jelas jika menolak

---

### 3. MELIHAT REKAP KEUANGAN

![Screenshot: Halaman Rekap Keuangan dengan Card Statistik]

#### Langkah-langkah:
1. Klik menu **"Rekap Keuangan"**
2. Lihat ringkasan:
   - Total Pendaftar
   - Total Pembayaran Diterima
   - Total Nominal Masuk
   - Pending Verifikasi
3. Filter berdasarkan:
   - Periode
   - Gelombang
   - Status
4. Klik **"Export"** untuk download laporan

---

### 4. MELIHAT RIWAYAT PEMBAYARAN

![Screenshot: Halaman Riwayat Pembayaran]

#### Langkah-langkah:
1. Klik menu **"Riwayat"**
2. Lihat semua pembayaran yang sudah diverifikasi
3. Filter berdasarkan status dan tanggal
4. Klik **"Detail"** untuk melihat kembali

---

### 5. MEMBUAT LAPORAN KEUANGAN

![Screenshot: Halaman Laporan Keuangan dengan Filter]

#### Langkah-langkah:
1. Klik menu **"Laporan"**
2. Pilih jenis laporan:
   - Laporan Harian
   - Laporan Bulanan
   - Laporan per Gelombang
3. Pilih periode
4. Klik **"Generate"**
5. Klik **"Export"** untuk download Excel

---

## PANDUAN UNTUK KEPALA SEKOLAH

### 1. LOGIN KEPALA SEKOLAH

![Screenshot: Dashboard Kepala Sekolah dengan Overview Lengkap]

#### Langkah-langkah:
1. Buka halaman login
2. Masukkan email dan password kepala sekolah
3. Klik **"Login"**
4. Anda akan masuk ke Dashboard Kepala Sekolah

---

### 2. MELIHAT DASHBOARD

![Screenshot: Dashboard dengan Grafik dan Chart]

#### Informasi di Dashboard:
- Total Pendaftar
- Pendaftar per Jurusan
- Pendaftar per Gelombang
- Status Verifikasi
- Status Pembayaran
- Grafik Statistik
- Peta Sebaran

#### Langkah-langkah:
1. Dashboard otomatis tampil setelah login
2. Lihat ringkasan data pendaftaran
3. Klik grafik untuk detail
4. Gunakan filter untuk periode tertentu

---

### 3. MELIHAT LAPORAN

![Screenshot: Halaman Laporan Kepala Sekolah]

#### Langkah-langkah:
1. Klik menu **"Laporan"**
2. Pilih jenis laporan yang diinginkan
3. Pilih periode
4. Klik **"Lihat Laporan"**
5. Klik **"Export"** untuk download

---

## FAQ (Frequently Asked Questions)

### Untuk Pendaftar:

**Q: Saya lupa password, bagaimana cara reset?**
A: Klik "Lupa Password" di halaman login, masukkan email, dan ikuti instruksi yang dikirim ke email Anda.

**Q: Berkas saya ditolak, apa yang harus saya lakukan?**
A: Baca catatan dari verifikator, perbaiki berkas sesuai catatan, lalu upload ulang di menu "Upload Berkas".

**Q: Berapa lama proses verifikasi?**
A: Verifikasi berkas: 1-3 hari kerja. Verifikasi pembayaran: 1-2 hari kerja.

**Q: Apakah bisa mengubah data setelah submit?**
A: Tidak bisa. Pastikan semua data benar sebelum submit. Jika ada kesalahan, hubungi admin.

**Q: Biaya pendaftaran berapa?**
A: Biaya tergantung gelombang yang dipilih. Lihat di menu "Pembayaran" setelah berkas diterima.

**Q: Apakah bisa daftar lebih dari 1 jurusan?**
A: Tidak. Setiap pendaftar hanya bisa memilih 1 jurusan.

---

### Untuk Admin:

**Q: Bagaimana cara backup data?**
A: Export data secara berkala melalui menu "Laporan" atau "Monitoring".

**Q: User tidak bisa login, kenapa?**
A: Periksa status user di "Kelola User". Pastikan status "Aktif".

**Q: Bagaimana cara menghapus data pendaftar?**
A: Tidak disarankan menghapus data. Gunakan status untuk mengelola data.

---

### Untuk Verifikator:

**Q: Berkas tidak bisa dibuka, bagaimana?**
A: Pastikan browser mendukung format file. Coba download file terlebih dahulu.

**Q: Apakah bisa membatalkan verifikasi?**
A: Tidak bisa. Pastikan verifikasi dilakukan dengan teliti.

---

### Untuk Keuangan:

**Q: Pembayaran tidak sesuai nominal, bagaimana?**
A: Tolak pembayaran dan beri catatan untuk upload bukti yang benar.

**Q: Bagaimana jika ada pembayaran ganda?**
A: Hubungi admin untuk koordinasi pengembalian dana.

---

## KONTAK SUPPORT

Jika mengalami kendala teknis atau membutuhkan bantuan:

- **Email**: support@sekolah.ac.id
- **WhatsApp**: 0812-3456-7890
- **Jam Operasional**: Senin-Jumat, 08:00-16:00 WIB

---

## CATATAN PENTING

1. **Keamanan Akun**
   - Jangan bagikan password ke siapapun
   - Logout setelah selesai menggunakan sistem
   - Gunakan password yang kuat

2. **Data Pribadi**
   - Pastikan semua data yang diisi benar dan sesuai dokumen
   - Data yang salah dapat menyebabkan penolakan

3. **Berkas**
   - Upload berkas dengan kualitas baik
   - Pastikan file tidak corrupt
   - Simpan backup berkas di komputer Anda

4. **Pembayaran**
   - Simpan bukti pembayaran asli
   - Upload bukti yang jelas dan terbaca
   - Catat nomor pendaftaran Anda

5. **Jadwal**
   - Perhatikan batas waktu pendaftaran
   - Daftar sebelum gelombang ditutup
   - Cek status secara berkala

---

**Terima kasih telah menggunakan Sistem Penerimaan Mahasiswa Baru!**

*Dokumen ini terakhir diupdate: November 2025*
