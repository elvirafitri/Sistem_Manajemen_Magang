<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Kata Sandi — {{ config('app.name') }}</title>
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
            width: 100%; max-width: 400px;
            margin: 1.5rem;
        }
        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 0.8125rem; font-weight: 600; color: #009da5;
            text-decoration: none; margin-bottom: 2rem;
            transition: color 0.2s;
        }
        .back-link:hover { color: #007a83; }

        .logo-wrap { text-align: center; margin-bottom: 1.75rem; }
        .logo-wrap img { height: 48px; width: auto; object-fit: contain; }

        .icon-wrap {
            width: 60px; height: 60px;
            border-radius: 16px;
            background: linear-gradient(135deg, #e0f7f8, #b2ebf2);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem;
        }
        .icon-wrap svg { color: #009da5; }

        h1 { font-size: 1.4rem; font-weight: 800; color: #0f172a; margin: 0 0 8px; text-align: center; }
        .subtitle { font-size: 0.875rem; color: #64748b; text-align: center; line-height: 1.6; margin: 0 0 2rem; }

        .field { margin-bottom: 1.25rem; }
        .field label { display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; top: 0; bottom: 0; left: 13px;
            display: flex; align-items: center; color: #94a3b8;
            pointer-events: none; transition: color 0.2s;
        }
        .input-wrap:focus-within .input-icon { color: #009da5; }
        input[type="email"] {
            width: 100%;
            padding: 12px 16px 12px 40px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.875rem;
            color: #0f172a;
            background: #f8fafc;
            outline: none;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
        }
        input[type="email"]::placeholder { color: #94a3b8; }
        input[type="email"]:focus {
            border-color: #009da5;
            background: #fff;
            box-shadow: 0 0 0 3.5px rgba(0,157,165,0.13);
        }
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
            transition: transform 0.2s, box-shadow 0.2s, filter 0.2s;
            margin-top: 0.25rem;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,157,165,0.45); filter: brightness(1.05); }
        .btn:active { transform: translateY(0); }
        .btn:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

        /* Alerts */
        .alert { border-radius: 10px; padding: 12px 14px; font-size: 0.8125rem; display: flex; align-items: flex-start; gap: 9px; margin-bottom: 1.25rem; }
        .alert-error  { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert svg { flex-shrink: 0; margin-top: 1px; }
    </style>
</head>
<body>
    <div class="card">
        <a href="{{ route('login') }}" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke halaman masuk
        </a>

        <div class="logo-wrap">
            <img src="{{ asset('images/logo-rs-awalbros.png') }}" alt="RS Awal Bros" style="height: 48px; width: auto; object-fit: contain;">
        </div>

        <div class="icon-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
        </div>

        <h1>Lupa Kata Sandi?</h1>
        <p class="subtitle">Masukkan email Anda dan kami akan membantu Anda mengatur ulang kata sandi.</p>

        {{-- Success info --}}
        @if(session('success') || session('status'))
            <div class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p style="margin:0;font-weight:700;">Permintaan Dikirim</p>
                    <p style="margin:4px 0 0;opacity:0.85;">
                        @if(session('success') == 'We have emailed your password reset link.' || session('status') == 'We have emailed your password reset link.')
                            Kami telah mengirimkan tautan atur ulang kata sandi ke email Anda.
                        @else
                            {{ session('success') ?? session('status') }}
                        @endif
                    </p>
                </div>
            </div>
        @endif

        {{-- Error --}}
        @if($errors->any())
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <ul style="margin:0;padding-left:4px;list-style:disc;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('password.email') }}" id="forgotForm">
            @csrf
            <div class="field">
                <label for="email">Alamat Email</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <input id="email" name="email" type="email"
                           value="{{ old('email') }}" required autofocus
                           placeholder="nama@awalbros.com">
                </div>
            </div>
            <button type="submit" id="submitBtn" class="btn">Kirim Permintaan Reset</button>
        </form>
    </div>

    <script>
        document.getElementById('forgotForm').addEventListener('submit', function () {
            var btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<svg style="display:inline;width:15px;height:15px;vertical-align:-2px;margin-right:6px;animation:spin 1s linear infinite" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity=".25"/><path fill="currentColor" opacity=".75" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>Mengirim…';
        });
    </script>
    <style>@keyframes spin{to{transform:rotate(360deg)}}</style>
</body>
</html>
