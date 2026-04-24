<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Registrasi</title>
</head>

<body
    style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f4f8; margin: 0; padding: 40px 20px; color: #333333;">
    <div
        style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">

        <!-- Header -->
        <div style="background: linear-gradient(135deg, #003b73 0%, #0074b7 100%); padding: 30px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 28px; letter-spacing: 1px;">SPMB System</h1>
            <p style="color: #e0f2fe; margin: 8px 0 0 0; font-size: 14px; opacity: 0.9;">Sistem Penerimaan Peserta Didik
                Baru</p>
        </div>

        <!-- Body -->
        <div style="padding: 40px 30px;">
            <h2 style="color: #003b73; margin-top: 0; margin-bottom: 20px; font-size: 22px;">Halo, {{ $nama }}! 👋</h2>

            <p style="color: #555555; line-height: 1.7; margin-bottom: 25px; font-size: 15px;">
                Terima kasih telah melakukan pendaftaran di sistem SPMB kami. Untuk melanjutkan ke tahap berikutnya,
                kami perlu memverifikasi alamat email Anda. Silakan masukkan kode OTP berikut:
            </p>

            <!-- OTP Box -->
            <div style="text-align: center; margin: 25px 0;">
                <div
                    style="background-color: #f0f9ff; border: 2px dashed #0074b7; padding: 15px 30px; border-radius: 10px; display: inline-block;">
                    <span
                        style="display: block; font-size: 11px; color: #0074b7; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; letter-spacing: 1px;">KODE
                        VERIFIKASI ANDA</span>
                    <h1 style="color: #003b73; margin: 0; font-size: 32px; letter-spacing: 6px; font-weight: 800;">
                        {{ $otp }}
                    </h1>
                </div>
            </div>

            <!-- Alert Info -->
            <div
                style="background-color: #fff8e6; border-left: 4px solid #f59e0b; padding: 15px 20px; border-radius: 4px; margin: 30px 0;">
                <p style="margin: 0; color: #b45309; font-size: 14px; line-height: 1.5;">
                    <strong style="color: #92400e;">⚠️ Perhatian:</strong> Kode OTP ini bersifat rahasia, hanya berlaku
                    selama <strong style="color: #92400e;">5 menit</strong> dan hanya dapat digunakan satu kali. Jangan
                    berikan kode ini kepada siapapun.
                </p>
            </div>

            <p style="color: #777777; line-height: 1.6; font-size: 14px; margin-top: 30px;">
                Jika Anda tidak merasa melakukan pendaftaran ini, abaikan saja email ini. Keamanan akun Anda tetap
                terjamin.
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 25px 30px; text-align: center;">
            <p style="color: #94a3b8; font-size: 13px; margin: 0 0 10px 0; line-height: 1.5;">
                Pesan ini dibuat secara otomatis oleh sistem, mohon tidak membalas ke alamat email ini.
            </p>
            <p style="color: #64748b; font-size: 12px; margin: 0; font-weight: 600;">
                &copy; {{ date('Y') }} SPMB System. Hak Cipta Dilindungi.
            </p>
        </div>

    </div>
</body>

</html>