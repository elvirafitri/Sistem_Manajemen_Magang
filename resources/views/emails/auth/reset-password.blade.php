<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — RS Awal Bros</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f4f7f6; padding-bottom: 40px; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; border-spacing: 0; color: #4a4a4a; border-radius: 8px; overflow: hidden; margin-top: 40px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background: linear-gradient(135deg, #009da5 0%, #006b77 100%); padding: 30px; text-align: center; }
        .header img { height: 50px; width: auto; }
        .content { padding: 40px 30px; line-height: 1.6; }
        .content h1 { font-size: 22px; color: #1a1a1a; margin-bottom: 20px; font-weight: 700; }
        .content p { font-size: 16px; color: #555555; margin-bottom: 25px; }
        .button-container { text-align: center; margin: 35px 0; }
        .button { background-color: #009da5; color: #ffffff !important; padding: 14px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px; display: inline-block; box-shadow: 0 4px 12px rgba(0,157,165,0.25); }
        .footer { padding: 20px 30px; text-align: center; font-size: 12px; color: #999999; border-top: 1px solid #eeeeee; }
        .footer p { margin: 5px 0; }
        .notice { font-size: 13px; color: #888888; border-left: 4px solid #eeeeee; padding-left: 15px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main">
            <tr>
                <td class="header">
                    {{-- Ganti logo dengan teks jika logo tidak dapat diakses secara publik --}}
                    <div style="color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: 2px;">RS AWAL BROS</div>
                </td>
            </tr>
            <tr>
                <td class="content">
                    <h1>Halo, {{ $name }}!</h1>
                    <p>Anda menerima email ini karena kami menerima permintaan pengaturan ulang password untuk akun Anda di <strong>Sistem Manajemen Magang RS Awal Bros</strong>.</p>
                    <p>Silakan klik tombol di bawah ini untuk melanjutkan proses pengaturan ulang password:</p>
                    <div class="button-container">
                        <a href="{{ $url }}" class="button">Atur Ulang Password</a>
                    </div>
                    <p>Link pengaturan ulang password ini akan kedaluwarsa dalam <strong>{{ $count }} menit</strong>.</p>
                    <div class="notice">
                        Jika Anda tidak merasa melakukan permintaan ini, abaikan saja email ini. Keamanan akun Anda tetap terjaga.
                    </div>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    <p>&copy; {{ date('Y') }} RS Awal Bros. Semua Hak Dilindungi.</p>
                    <p>Sistem Manajemen Magang v1.0</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
