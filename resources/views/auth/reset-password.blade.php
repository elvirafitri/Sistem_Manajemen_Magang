<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atur Ulang Kata Sandi — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Inter', system-ui, sans-serif;
            background: #f0f4f8;
        }
        .card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1), 0 4px 16px rgba(0,0,0,0.06);
            padding: 2.5rem 2.25rem;
            width: 100%; max-width: 420px;
            margin: 1.5rem;
        }
        .logo-wrap { text-align: center; margin-bottom: 1.75rem; }
        .logo-wrap img { height: 48px; width: auto; object-fit: contain; }

        .icon-wrap {
            width: 60px; height: 60px;
            border-radius: 16px;
            background: linear-gradient(135deg, #e0f2fe, #bae6fd);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem;
        }
        .icon-wrap svg { color: #0284c7; }

        h1 { font-size: 1.4rem; font-weight: 800; color: #0f172a; margin: 0 0 8px; text-align: center; }
        .subtitle { font-size: 0.875rem; color: #64748b; text-align: center; line-height: 1.6; margin: 0 0 2rem; }

        .field { margin-bottom: 1.25rem; }
        .field label { display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; inset-y: 0; left: 13px;
            display: flex; align-items: center; color: #94a3b8;
            pointer-events: none;
        }
        input {
            width: 100%;
            padding: 12px 16px 12px 40px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.875rem;
            color: #0f172a;
            background: #f8fafc;
            outline: none;
            transition: all 0.25s;
        }
        input:focus { border-color: #009da5; background: #fff; box-shadow: 0 0 0 3.5px rgba(0,157,165,0.13); }
        
        .btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #009da5 0%, #006b77 100%);
            color: #fff;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(0,157,165,0.35);
            transition: all 0.2s;
            margin-top: 0.5rem;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,157,165,0.45); }
        
        .alert { border-radius: 10px; padding: 12px 14px; font-size: 0.8125rem; margin-bottom: 1.5rem; border: 1px solid #fecaca; background: #fef2f2; color: #b91c1c; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo-wrap">
            <img src="{{ asset('images/logo-rs-awalbros.png') }}" alt="RS Awal Bros">
        </div>

        <div class="icon-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        <h1>Atur Ulang Kata Sandi</h1>
        <p class="subtitle">Silakan masukkan kata sandi baru Anda untuk mengamankan kembali akun Anda.</p>

        @if($errors->any())
            <div class="alert">
                <ul style="margin:0;padding-left:1.25rem;">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="field">
                <label for="email">Email</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    <input type="email" name="email" id="email" value="{{ $email ?? old('email') }}" required readonly>
                </div>
            </div>

            <div class="field">
                <label for="password">Kata Sandi Baru</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <input type="password" name="password" id="password" required autofocus placeholder="Minimal 8 karakter">
                </div>
            </div>

            <div class="field">
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </span>
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi kata sandi">
                </div>
            </div>

            <button type="submit" class="btn">Simpan Kata Sandi Baru</button>
        </form>
    </div>
</body>
</html>
