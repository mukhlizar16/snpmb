<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SNPMB') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ── Root layout ─────────────────────────────────────────── */
        .auth-root {
            display: grid;
            grid-template-columns: 420px 1fr;
            min-height: 100vh;
        }

        /* ── Left / brand panel ──────────────────────────────────── */
        .auth-brand {
            background: #0f172a;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px 44px;
        }

        /* dot-grid texture */
        .auth-brand::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle, rgba(99,130,239,.18) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        /* right-edge accent line */
        .auth-brand::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 2px; height: 100%;
            background: linear-gradient(180deg, transparent 0%, #2563eb 30%, #2563eb 70%, transparent 100%);
        }

        .auth-brand-top { position: relative; z-index: 1; }
        .auth-brand-bottom { position: relative; z-index: 1; }

        /* status chip */
        .brand-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(37,99,235,.12);
            border: 1px solid rgba(37,99,235,.28);
            border-radius: 6px;
            padding: 4px 11px;
            font-family: 'IBM Plex Mono', monospace;
            font-size: .625rem;
            color: #60a5fa;
            letter-spacing: .1em;
            margin-bottom: 30px;
        }

        .brand-chip-dot {
            width: 6px; height: 6px;
            background: #2563eb;
            border-radius: 50%;
            animation: pulseDot 2.4s ease-in-out infinite;
        }

        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: .4; transform: scale(.75); }
        }

        /* main wordmark */
        .brand-wordmark {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 3.75rem;
            font-weight: 700;
            color: #f1f5f9;
            line-height: 1;
            letter-spacing: -.03em;
            margin-bottom: 2px;
        }

        .brand-wordmark-accent { color: #2563eb; }

        .brand-fullname {
            font-size: .8125rem;
            color: #64748b;
            line-height: 1.6;
            margin-top: 10px;
            max-width: 260px;
            font-style: italic;
        }

        .brand-rule {
            width: 36px; height: 2px;
            background: #2563eb;
            margin: 28px 0;
        }

        /* feature bullets */
        .brand-features {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .brand-feature {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .brand-feature-icon {
            width: 30px; height: 30px;
            background: rgba(37,99,235,.1);
            border: 1px solid rgba(37,99,235,.22);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .brand-feature-icon svg {
            width: 14px; height: 14px;
            stroke: #60a5fa;
        }

        .brand-feature-title {
            font-size: .8rem;
            font-weight: 600;
            color: #cbd5e1;
            line-height: 1.3;
        }

        .brand-feature-sub {
            font-size: .7rem;
            color: #475569;
            margin-top: 2px;
        }

        .brand-footer-text {
            font-family: 'IBM Plex Mono', monospace;
            font-size: .625rem;
            color: #334155;
            letter-spacing: .1em;
        }

        /* ── Right / form panel ──────────────────────────────────── */
        .auth-form-panel {
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
        }

        .auth-form-inner {
            width: 100%;
            max-width: 390px;
        }

        /* ── Form elements (used by login + register views) ──────── */
        .auth-heading {
            font-size: 1.625rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -.025em;
            margin: 0 0 6px;
        }

        .auth-subheading {
            font-size: .875rem;
            color: #94a3b8;
            margin: 0 0 30px;
        }

        .auth-status-msg {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: .8125rem;
            color: #15803d;
            margin-bottom: 20px;
        }

        .auth-field { margin-bottom: 16px; }

        .auth-label {
            display: block;
            font-size: .75rem;
            font-weight: 600;
            color: #374151;
            letter-spacing: .02em;
            margin-bottom: 7px;
        }

        .auth-input {
            width: 100%;
            height: 48px;
            padding: 0 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .875rem;
            color: #0f172a;
            background: #f8fafc;
            outline: none;
            transition: border-color .15s, background .15s, box-shadow .15s;
            -webkit-appearance: none;
            appearance: none;
        }

        .auth-input:focus {
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }

        .auth-input::placeholder { color: #cbd5e1; }

        .auth-input.is-error { border-color: #fca5a5; background: #fff; }

        .auth-error-msg {
            font-size: .7rem;
            color: #ef4444;
            margin-top: 5px;
        }

        .auth-row-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .auth-check-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .auth-check-label input[type="checkbox"] {
            width: 16px; height: 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 4px;
            accent-color: #2563eb;
            cursor: pointer;
            flex-shrink: 0;
        }

        .auth-check-text {
            font-size: .8125rem;
            color: #64748b;
        }

        .auth-link {
            font-size: .8125rem;
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
        }

        .auth-link:hover { text-decoration: underline; }

        .auth-submit {
            width: 100%;
            height: 48px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: .9375rem;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: -.01em;
            transition: background .15s, transform .1s;
            position: relative;
            overflow: hidden;
        }

        .auth-submit::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.1) 0%, transparent 55%);
            pointer-events: none;
        }

        .auth-submit:hover { background: #1d4ed8; }
        .auth-submit:active { transform: scale(.99); }

        .auth-alt-link {
            text-align: center;
            margin-top: 20px;
            font-size: .8125rem;
            color: #94a3b8;
        }

        .auth-alt-link a {
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
        }

        .auth-alt-link a:hover { text-decoration: underline; }

        /* ── Mobile ──────────────────────────────────────────────── */
        @media (max-width: 820px) {
            .auth-root {
                grid-template-columns: 1fr;
                grid-template-rows: auto 1fr;
            }

            .auth-brand {
                padding: 24px 28px;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }

            .auth-brand::after { display: none; }

            .auth-brand-top { display: flex; align-items: center; gap: 16px; }
            .auth-brand-bottom { display: none; }

            .brand-chip { margin-bottom: 0; }
            .brand-wordmark { font-size: 2rem; margin-bottom: 0; }
            .brand-fullname { display: none; }
            .brand-rule { display: none; }
            .brand-features { display: none; }

            .auth-form-panel {
                padding: 36px 24px 48px;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <div class="auth-root">

        {{-- ── Left brand panel ── --}}
        <aside class="auth-brand">
            <div class="auth-brand-top">
                <div class="brand-chip">
                    <div class="brand-chip-dot"></div>
                    SISTEM AKTIF &middot; TA 2025/2026
                </div>

                <div class="brand-wordmark">
                    SNP<span class="brand-wordmark-accent">MB</span>
                </div>
                <div class="brand-fullname">
                    Seleksi Nasional<br>Penerimaan Mahasiswa Baru
                </div>

                <div class="brand-rule"></div>

                <div class="brand-features">
                    <div class="brand-feature">
                        <div class="brand-feature-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="brand-feature-title">Validasi Data Otomatis</div>
                            <div class="brand-feature-sub">Pemeriksaan integritas saat import berlangsung</div>
                        </div>
                    </div>

                    <div class="brand-feature">
                        <div class="brand-feature-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </div>
                        <div>
                            <div class="brand-feature-title">Import CSV & Excel</div>
                            <div class="brand-feature-sub">Proses ratusan ribu baris data sekaligus</div>
                        </div>
                    </div>

                    <div class="brand-feature">
                        <div class="brand-feature-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5" />
                            </svg>
                        </div>
                        <div>
                            <div class="brand-feature-title">Analisis & Rekap Data</div>
                            <div class="brand-feature-sub">Tabel interaktif dan ekspor laporan CSV</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="auth-brand-bottom">
                <div class="brand-footer-text">© {{ date('Y') }} SNPMB &middot; BELMAWA KEMDIKBUD</div>
            </div>
        </aside>

        {{-- ── Right form panel ── --}}
        <main class="auth-form-panel">
            <div class="auth-form-inner">
                {{ $slot }}
            </div>
        </main>

    </div>
</body>

</html>
