<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SNPMB — Sistem Manajemen Data Nasional</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=ibm-plex-sans:400,500,600,700,800|ibm-plex-mono:400,500,700&display=swap" rel="stylesheet"/>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'IBM Plex Sans', sans-serif;
            background: #0b1623;
            color: #f1f5f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Dot grid overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(rgba(99,179,237,.07) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
            z-index: 0;
        }

        /* Blue glow top-right */
        body::after {
            content: '';
            position: fixed;
            top: -80px; right: -80px;
            width: 480px; height: 480px;
            background: radial-gradient(circle, rgba(59,130,246,.12), transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .page {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── Nav ─────────────────────────────── */
        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 40px;
            border-bottom: 1px solid #18293e;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .brand-icon {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 8px rgba(59,130,246,.3);
        }

        .brand-text-main {
            font-family: 'IBM Plex Mono', monospace;
            font-size: .85rem; font-weight: 700;
            color: #f1f5f9; letter-spacing: .06em;
            display: block; line-height: 1.2;
        }

        .brand-text-sub {
            font-family: 'IBM Plex Mono', monospace;
            font-size: .55rem; color: #2d4a66;
            letter-spacing: .1em; text-transform: uppercase;
            display: block;
        }

        .nav-auth {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-ghost {
            font-size: .8125rem; font-weight: 500;
            color: #64748b;
            padding: 7px 14px;
            border-radius: 8px;
            text-decoration: none;
            transition: background .15s, color .15s;
            border: none; background: none; cursor: pointer;
        }

        .btn-ghost:hover { background: #18293e; color: #cbd5e1; }

        .btn-primary {
            font-size: .8125rem; font-weight: 600;
            color: #fff;
            background: #2563eb;
            padding: 7px 16px;
            border-radius: 8px;
            text-decoration: none;
            transition: background .15s;
            border: none; cursor: pointer;
        }

        .btn-primary:hover { background: #1d4ed8; }

        /* ── Hero ────────────────────────────── */
        .hero {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 40px;
            text-align: center;
        }

        .hero-inner {
            max-width: 640px;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'IBM Plex Mono', monospace;
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #3b82f6;
            background: rgba(59,130,246,.1);
            border: 1px solid rgba(59,130,246,.2);
            padding: 5px 12px;
            border-radius: 999px;
            margin-bottom: 28px;
        }

        .hero-tag-dot {
            width: 6px; height: 6px;
            background: #3b82f6;
            border-radius: 50%;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: .4; transform: scale(.7); }
        }

        h1 {
            font-size: clamp(2.2rem, 5vw, 3.5rem);
            font-weight: 800;
            color: #f1f5f9;
            letter-spacing: -.04em;
            line-height: 1.05;
            margin-bottom: 20px;
        }

        h1 .accent { color: #60a5fa; }

        .hero-sub {
            font-size: .9375rem;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 40px;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-lg {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: .9rem;
            font-weight: 600;
            padding: 11px 24px;
            border-radius: 10px;
            text-decoration: none;
            transition: background .15s, transform .1s;
        }

        .btn-lg:hover { transform: translateY(-1px); }

        .btn-lg.primary { background: #2563eb; color: #fff; }
        .btn-lg.primary:hover { background: #1d4ed8; }

        .btn-lg.outline {
            background: transparent;
            color: #64748b;
            border: 1px solid #18293e;
        }

        .btn-lg.outline:hover { background: #18293e; color: #cbd5e1; }

        /* ── Footer ──────────────────────────── */
        .footer {
            border-top: 1px solid #18293e;
            padding: 18px 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer p {
            font-family: 'IBM Plex Mono', monospace;
            font-size: .6rem;
            color: #1e3a5f;
            letter-spacing: .08em;
            text-align: center;
        }

        @media (max-width: 540px) {
            .nav { padding: 16px 20px; }
            .hero { padding: 60px 20px; }
            .footer { padding: 16px 20px; }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- Nav --}}
    <nav class="nav">
        <a href="/" class="brand">
            <div class="brand-icon">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="white">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
            </div>
            <div>
                <span class="brand-text-main">SNPMB</span>
                <span class="brand-text-sub">Data System</span>
            </div>
        </a>

        <div class="nav-auth">
            @auth
                <a href="{{ route('import.index') }}" class="btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-ghost">Masuk</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                @endif
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <main class="hero">
        <div class="hero-inner">
            <div class="hero-tag">
                <span class="hero-tag-dot"></span>
                Sistem Nasional Penerimaan Mahasiswa Baru
            </div>

            <h1>
                Manajemen Data<br>
                <span class="accent">SNPMB</span>
            </h1>

            <p class="hero-sub">
                Platform import dan pengelolaan data seleksi mahasiswa baru
                secara terpusat. Proses file CSV/XLSX dengan validasi otomatis.
            </p>

            <div class="hero-actions">
                @auth
                    <a href="{{ route('import.index') }}" class="btn-lg primary">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Mulai Import
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-lg primary">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Masuk ke Sistem
                    </a>
                    <a href="{{ url('/dashboard') }}" class="btn-lg outline">
                        Lihat Dashboard
                    </a>
                @endauth
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="footer">
        <p>SNPMB · SISTEM MANAJEMEN DATA NASIONAL · {{ date('Y') }}</p>
    </footer>

</div>
</body>
</html>
