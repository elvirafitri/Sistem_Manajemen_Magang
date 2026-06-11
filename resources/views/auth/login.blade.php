<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            font-family: 'Inter', system-ui, sans-serif;
            background: #f0f4f8;
        }

        /* ─── Left panel ─────────────────────────────── */
        .left-panel {
            display: none;
            flex: 1;
            position: relative;
            overflow: hidden;
            background: linear-gradient(rgba(0, 45, 52, 0.4), rgba(0, 95, 107, 0.7)), url("{{ asset('images/image.png') }}");
            background-size: cover;
            background-position: center;
            padding: 3rem;
            flex-direction: column;
            justify-content: space-between;
        }
        @media (min-width: 900px) { .left-panel { display: flex; } }

        .left-panel .grid-bg {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 44px 44px;
        }
        .left-panel .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
        }
        .blob-a { width:500px;height:500px;background:rgba(0,220,230,0.18);top:-160px;right:-140px;animation:bfloat 12s ease-in-out infinite; }
        .blob-b { width:380px;height:380px;background:rgba(0,80,100,0.35);bottom:-120px;left:-100px;animation:bfloat 15s ease-in-out infinite 3s; }
        .blob-c { width:260px;height:260px;background:rgba(255,255,255,0.07);top:42%;left:25%;animation:bfloat 9s ease-in-out infinite 1.5s; }

        @keyframes bfloat {
            0%,100% { transform: translateY(0) scale(1); }
            50%      { transform: translateY(-24px) scale(1.04); }
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
        }
        .brand-badge img { height: 44px; width: auto; filter: drop-shadow(0 2px 8px rgba(0,0,0,0.3)); }

        .left-quote {
            position: relative; z-index: 1;
        }
        .left-quote h2 {
            font-size: 2.1rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.25;
            letter-spacing: -0.02em;
            margin: 0 0 1rem;
        }
        .left-quote p {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.6);
            line-height: 1.7;
            margin: 0;
        }
        .left-dots {
            display: flex; gap: 8px;
            position: relative; z-index: 1;
        }
        .left-dots span {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: rgba(255,255,255,0.25);
        }
        .left-dots span.active { background: #fff; width: 24px; border-radius: 4px; }

        /* ─── Right panel (form) ─────────────────────── */
        .right-panel {
            flex: 0 0 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.25rem;
            background: #fff;
        }
        @media (min-width: 900px) {
            .right-panel { flex: 0 0 460px; }
        }

        .form-box { width: 100%; max-width: 380px; }

        /* Logo mobile */
        .mobile-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2.5rem;
        }
        .mobile-logo img { height: 52px; width: auto; object-fit: contain; }
        @media (min-width: 900px) { .mobile-logo { display: none; } }

        /* Heading */
        .form-heading { margin-bottom: 2rem; }
        .form-heading h1 {
            font-size: 1.65rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.025em;
            margin: 0 0 6px;
        }
        .form-heading p { font-size: 0.875rem; color: #64748b; margin: 0; }

        /* Input group */
        .field { margin-bottom: 1.25rem; }
        .field label { display: block; font-size: 0.8125rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; top: 0; bottom: 0; left: 13px;
            display: flex; align-items: center;
            color: #94a3b8; pointer-events: none;
            transition: color 0.2s;
        }
        .input-wrap:focus-within .input-icon { color: #009da5; }
        .field input[type="email"],
        .field input[type="password"] {
            width: 100%;
            padding: 12px 42px 12px 40px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.875rem;
            color: #0f172a;
            background: #f8fafc;
            outline: none;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
        }
        .field input::placeholder { color: #94a3b8; }
        .field input:focus {
            border-color: #009da5;
            background: #fff;
            box-shadow: 0 0 0 3.5px rgba(0,157,165,0.13);
        }
        .pass-toggle {
            position: absolute; top: 0; bottom: 0; right: 12px;
            display: flex; align-items: center;
            color: #94a3b8; cursor: pointer;
            background: none; border: none; padding: 4px;
            transition: color 0.2s;
        }
        .pass-toggle:hover { color: #009da5; }

        /* Row: remember + forgot */
        .row-extras {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .check-label {
            display: flex; align-items: center; gap: 8px;
            font-size: 0.8125rem; color: #475569; cursor: pointer;
        }
        .check-label input { accent-color: #009da5; width: 15px; height: 15px; }
        .forgot-link {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #009da5;
            text-decoration: none;
            transition: color 0.2s;
        }
        .forgot-link:hover { color: #007a83; text-decoration: underline; }

        /* Submit */
        .btn-submit {
            width: 100%;
            padding: 13.5px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #009da5 0%, #006b77 100%);
            color: #fff;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,157,165,0.35), 0 1px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s, filter 0.2s;
        }
        .btn-submit::after {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.16), transparent);
            transition: left 0.5s;
        }
        .btn-submit:hover::after { left: 100%; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,157,165,0.45); filter: brightness(1.05); }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

        /* Divider */
        .divider { display: flex; align-items: center; gap: 12px; margin: 1.5rem 0; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e9eef4; }
        .divider span { font-size: 0.75rem; color: #94a3b8; font-weight: 500; }

        /* Alert */
        .alert {
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 0.8125rem;
            display: flex; align-items: flex-start; gap: 9px;
            margin-bottom: 1.25rem;
        }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
        .alert-error svg { color: #ef4444; flex-shrink: 0; margin-top: 1px; }
    </style>
</head>
<body>

    {{-- ── Left branding panel ── --}}
    <div class="left-panel">
        <div class="grid-bg"></div>
        <div class="blob blob-a"></div>
        <div class="blob blob-b"></div>
        <div class="blob blob-c"></div>

        <div class="brand-badge" style="position:relative;z-index:1;">
            <img src="{{ asset('images/logo-rs-awalbros.png') }}" alt="RS Awal Bros"
                 onerror="this.style.display='none'">
        </div>

        <div class="left-quote">
            <h2>Kelola Program&nbsp;Magang Lebih Mudah &amp; Efisien</h2>
            <p>Platform terpusat untuk memantau absensi, penilaian, izin, dan sertifikat peserta magang RS Awal Bros.</p>
        </div>

        <div class="left-dots">
            <span class="active"></span>
            <span></span>
            <span></span>
        </div>
    </div>

    {{-- ── Right form panel ── --}}
    <div class="right-panel">
        <div class="form-box">

            {{-- Logo (mobile only) --}}
            <div class="mobile-logo">
                <img src="{{ asset('images/logo-rs-awalbros.png') }}" alt="RS Awal Bros"
                     onerror="this.style.display='none'">
            </div>

            <div class="form-heading">
                <h1>Selamat Datang 👋</h1>
                <p>Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            {{-- Error alert --}}
            @if($errors->any())
                <div class="alert alert-error">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <ul style="list-style:disc;padding-left:4px;margin:0;">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('login') }}" id="loginForm">
                @csrf

                {{-- Email --}}
                <div class="field">
                    <label for="email">Alamat Email</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                               required autofocus placeholder="nama@awalbros.com">
                    </div>
                </div>

                {{-- Password --}}
                <div class="field">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input id="password" name="password" type="password"
                               required placeholder="••••••••">
                        <button type="button" id="togglePass" class="pass-toggle" tabindex="-1">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember + Forgot --}}
                <div class="row-extras">
                    <label class="check-label">
                        <input type="checkbox" name="remember">
                        Ingat saya
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa kata sandi?</a>
                </div>

                {{-- Submit --}}
                <button type="submit" id="submitBtn" class="btn-submit">Masuk</button>
            </form>

            <div class="divider"><span>Sistem Manajemen Magang</span></div>
            <p style="text-align:center;font-size:0.75rem;color:#94a3b8;">© {{ date('Y') }} RS Awal Bros. Hak Cipta Dilindungi Undang-Undang.</p>
        </div>
    </div>

    <script>
        // Toggle password visibility
        var passInput = document.getElementById('password');
        var eyeIcon   = document.getElementById('eyeIcon');
        document.getElementById('togglePass').addEventListener('click', function () {
            var show = passInput.type === 'password';
            passInput.type = show ? 'text' : 'password';
            eyeIcon.innerHTML = show
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        });

        // Loading state
        document.getElementById('loginForm').addEventListener('submit', function () {
            var btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<svg style="display:inline;width:16px;height:16px;vertical-align:-3px;margin-right:6px;animation:spin 1s linear infinite" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity=".25"/><path fill="currentColor" opacity=".75" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>Memproses…';
        });
    </script>
    <style>@keyframes spin{to{transform:rotate(360deg)}}</style>
</body>
</html>
