<x-app-layout>

    <style>
        .import-page,
        .import-page * {
            font-family: 'IBM Plex Sans', 'Figtree', sans-serif;
        }

        .import-page .mono {
            font-family: 'IBM Plex Mono', monospace;
        }

        /* Dot-grid background */
        .dot-bg {
            background-color: #f1f5f9;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* ── Hero panel ─────────────────────────────────────────── */
        .hero-panel {
            background: #0b1623;
            border-radius: 20px;
            padding: 40px 44px 36px;
            margin-bottom: 44px;
            position: relative;
            overflow: hidden;
        }

        .hero-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(99, 179, 237, .04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99, 179, 237, .04) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
        }

        .hero-panel::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 380px;
            height: 380px;
            background: radial-gradient(circle at top right, rgba(59, 130, 246, .14), transparent 68%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-sysline {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
        }

        .hero-sys-id {
            font-family: 'IBM Plex Mono', monospace;
            font-size: .6rem;
            font-weight: 700;
            color: #3b82f6;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .hero-sys-sep {
            font-family: 'IBM Plex Mono', monospace;
            font-size: .6rem;
            color: #1e3a5f;
            letter-spacing: .1em;
        }

        .hero-cursor {
            font-family: 'IBM Plex Mono', monospace;
            font-size: .65rem;
            color: #3b82f6;
            animation: cursor-blink 1.15s step-start infinite;
        }

        @keyframes cursor-blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: 0
            }
        }

        .hero-title {
            font-size: clamp(1.9rem, 4vw, 2.75rem);
            font-weight: 800;
            color: #f1f5f9;
            letter-spacing: -.035em;
            line-height: 1.08;
            margin-bottom: 14px;
        }

        .hero-title-accent {
            color: #60a5fa;
        }

        .hero-sub {
            font-size: .875rem;
            color: #475569;
            max-width: 500px;
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .hero-stats {
            display: inline-flex;
            border: 1px solid #1a2f46;
            border-radius: 12px;
            overflow: hidden;
        }

        .hero-stat {
            padding: 11px 22px;
            border-right: 1px solid #1a2f46;
            text-align: center;
        }

        .hero-stat:last-child {
            border-right: none;
        }

        .hero-stat-num {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 1.3rem;
            font-weight: 700;
            color: #e2e8f0;
            line-height: 1;
            display: block;
        }

        .hero-stat-label {
            font-size: .6rem;
            font-weight: 500;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: .09em;
            display: block;
            margin-top: 4px;
        }

        /* ── Section headers ────────────────────────────────────── */
        .section-header {
            position: relative;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            padding: 4px 0 20px;
            overflow: hidden;
        }

        .section-bar {
            width: 4px;
            min-height: 46px;
            border-radius: 4px;
            flex-shrink: 0;
            z-index: 1;
        }

        .section-meta {
            z-index: 1;
        }

        .section-badge {
            font-family: 'IBM Plex Mono', monospace;
            font-size: .58rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: 2px 9px;
            border-radius: 999px;
            display: inline-block;
            margin-bottom: 5px;
        }

        .section-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -.025em;
            display: block;
            line-height: 1.2;
        }

        .section-desc {
            font-size: .75rem;
            color: #94a3b8;
            display: block;
            margin-top: 2px;
        }

        .section-rule {
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, #e2e8f0, transparent);
            z-index: 1;
        }

        .section-ghost {
            position: absolute;
            right: -4px;
            top: 50%;
            transform: translateY(-56%);
            font-family: 'IBM Plex Mono', monospace;
            font-size: 5.5rem;
            font-weight: 800;
            color: #e8edf3;
            line-height: 1;
            letter-spacing: -.06em;
            user-select: none;
            pointer-events: none;
            z-index: 0;
        }

        /* Import card */
        .import-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: box-shadow .18s, transform .18s;
            opacity: 0;
            animation: slideUp .4s ease forwards;
        }

        .import-card:hover {
            box-shadow: 0 6px 24px rgba(15, 23, 42, .08);
            transform: translateY(-2px);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Drop zone */
        .drop-zone {
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            min-height: 108px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: border-color .15s, background .15s;
        }

        .drop-zone:hover,
        .drop-zone.dragging {
            border-color: var(--c);
            background: var(--cbg);
        }

        .drop-zone.has-file {
            border-style: solid;
            border-color: var(--c);
            background: var(--cbg);
        }

        /* Result stat */
        .stat {
            border-radius: 8px;
            padding: 8px 6px;
            text-align: center;
        }

        .stat-n {
            font-size: 1.15rem;
            font-weight: 700;
            line-height: 1;
        }

        .stat-l {
            font-size: .6rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* Spinner */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spin {
            animation: spin .7s linear infinite;
        }

        /* Pop */
        @keyframes pop {
            from {
                opacity: 0;
                transform: scale(.93);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .pop {
            animation: pop .16s ease forwards;
        }

        /* ── Responsive grid layouts (tidak bergantung Tailwind JIT) ── */
        .cards-4 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        .cards-3 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        @media (min-width: 640px) {
            .cards-4 {
                grid-template-columns: repeat(2, 1fr);
            }

            .cards-3 {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .cards-4 {
                grid-template-columns: repeat(4, 1fr);
            }
        }
    </style>

    <div class="min-h-screen py-10 import-page dot-bg">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- ── Hero ────────────────────────────────────────────────── --}}
            <div class="my-4 hero-panel">
                <div class="hero-content">

                    {{-- System ID line --}}
                    <div class="hero-sysline">
                        <span class="hero-sys-id">SYS:SNPMB</span>
                        <span class="hero-sys-sep">// DATA PIPELINE v1.0</span>
                        <span class="hero-cursor">▮</span>
                    </div>

                    {{-- Title --}}
                    <h1 class="hero-title">
                        Import Data<br>
                        <span class="hero-title-accent">Massal</span>
                    </h1>

                    {{-- Subtitle --}}
                    <p class="hero-sub">
                        Upload file CSV sesuai kategori. Ikuti urutan grup — data referensi
                        harus diimport lebih dahulu sebelum data inti dan detail.
                    </p>

                    {{-- Stats row --}}
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <span class="hero-stat-num">13</span>
                            <span class="hero-stat-label">Tipe Data</span>
                        </div>
                        <div class="hero-stat">
                            <span class="hero-stat-num">3</span>
                            <span class="hero-stat-label">Grup</span>
                        </div>
                        <div class="hero-stat">
                            <span class="hero-stat-num">~107K</span>
                            <span class="hero-stat-label">Baris Data</span>
                        </div>
                        <div class="hero-stat">
                            <span class="hero-stat-num">Async</span>
                            <span class="hero-stat-label">Queue</span>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── Flash ───────────────────────────────────────────────── --}}
            @if ($errors->any())
                <div class="flex items-start gap-3 px-5 py-4 mb-6 border border-red-200 bg-red-50 rounded-xl">
                    <svg class="w-4 h-4 text-red-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="mb-1 text-xs font-semibold text-red-700">Terdapat kesalahan</p>
                        @foreach ($errors->all() as $e)
                            <p class="text-xs text-red-600">· {{ $e }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ══════════════════════════════════════════════════════════
                 GRUP 1 — REFERENSI
            ═══════════════════════════════════════════════════════════════ --}}
            <div class="mb-10">
                <div class="section-header">
                    <div class="section-bar" style="background:#0369a1;"></div>
                    <div class="section-meta">
                        <span class="section-badge" style="background:#f0f9ff;color:#0369a1;">GRUP 1</span>
                        <span class="section-title">Data Referensi</span>
                        <span class="section-desc">Import pertama — tidak bergantung pada tabel lain</span>
                    </div>
                    <div class="section-rule"></div>
                    <span class="section-ghost">01</span>
                </div>

                <div class="cards-4">

                    {{-- REF JURUSAN --}}
                    @include('import._card', [
                        'title' => 'Ref. Jurusan',
                        'subtitle' => 'Kode & nama jurusan',
                        'type' => 'ref_jurusan',
                        'color' => '#0369a1',
                        'colorBg' => '#f0f9ff',
                        'stepLabel' => '1a',
                        'delay' => '0.05s',
                        'icon' =>
                            'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                    ])

                    {{-- REF MATA PELAJARAN --}}
                    @include('import._card', [
                        'title' => 'Ref. Mata Pelajaran',
                        'subtitle' => 'Kode & nama mapel',
                        'type' => 'ref_mata_pelajaran',
                        'color' => '#0369a1',
                        'colorBg' => '#f0f9ff',
                        'stepLabel' => '1b',
                        'delay' => '0.10s',
                        'icon' =>
                            'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                    ])

                    {{-- REF PORTOFOLIO --}}
                    @include('import._card', [
                        'title' => 'Ref. Portofolio',
                        'subtitle' => 'Kode & jenis portofolio',
                        'type' => 'ref_portofolio',
                        'color' => '#0369a1',
                        'colorBg' => '#f0f9ff',
                        'stepLabel' => '1c',
                        'delay' => '0.15s',
                        'icon' =>
                            'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
                    ])

                    {{-- DATA SEKOLAH --}}
                    @include('import._card', [
                        'title' => 'Data Sekolah',
                        'subtitle' => 'NPSN, akreditasi, wilayah',
                        'type' => 'data_sekolah',
                        'color' => '#0369a1',
                        'colorBg' => '#f0f9ff',
                        'stepLabel' => '1d',
                        'delay' => '0.20s',
                        'icon' =>
                            'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                    ])

                    {{-- REF MP PENDUKUNG --}}
                    @include('import._card', [
                        'title' => 'Ref. MP Pendukung',
                        'subtitle' => 'Mapel pendukung per prodi & jurusan',
                        'type' => 'ref_mp_pendukung',
                        'color' => '#0369a1',
                        'colorBg' => '#f0f9ff',
                        'stepLabel' => '1e',
                        'delay' => '0.25s',
                        'icon' =>
                            'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                    ])

                    {{-- REF INDEX SEKOLAH --}}
                    @include('import._card', [
                        'title' => 'Ref. Index Sekolah',
                        'subtitle' => 'Nilai index per sekolah (NPSN)',
                        'type' => 'ref_index_sekolah',
                        'color' => '#0369a1',
                        'colorBg' => '#f0f9ff',
                        'stepLabel' => '1f',
                        'delay' => '0.30s',
                        'icon' =>
                            'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
                    ])

                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════
                 GRUP 2 — DATA INTI
            ═══════════════════════════════════════════════════════════════ --}}
            <div class="mb-10">
                <div class="section-header">
                    <div class="section-bar" style="background:#2563eb;"></div>
                    <div class="section-meta">
                        <span class="section-badge" style="background:#eff6ff;color:#2563eb;">GRUP 2</span>
                        <span class="section-title">Data Inti</span>
                        <span class="section-desc">Bergantung pada Grup 1 — pastikan referensi sudah diimport</span>
                    </div>
                    <div class="section-rule"></div>
                    <span class="section-ghost">02</span>
                </div>

                <div class="cards-3">

                    {{-- DATA SISWA --}}
                    @include('import._card', [
                        'title' => 'Data Siswa',
                        'subtitle' => 'Master record peserta SNPMB',
                        'type' => 'data_siswa',
                        'color' => '#2563eb',
                        'colorBg' => '#eff6ff',
                        'stepLabel' => '2a',
                        'delay' => '0.05s',
                        'icon' =>
                            'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                    ])

                    {{-- DATA PILIHAN --}}
                    @include('import._card', [
                        'title' => 'Data Pilihan',
                        'subtitle' => 'Program studi pilihan siswa',
                        'type' => 'data_pilihan',
                        'color' => '#7c3aed',
                        'colorBg' => '#f5f3ff',
                        'stepLabel' => '2b',
                        'delay' => '0.12s',
                        'icon' =>
                            'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
                    ])

                    {{-- DATA PRESTASI --}}
                    @include('import._card', [
                        'title' => 'Data Prestasi',
                        'subtitle' => 'Capaian & penghargaan siswa',
                        'type' => 'data_prestasi',
                        'color' => '#0891b2',
                        'colorBg' => '#ecfeff',
                        'stepLabel' => '2c',
                        'delay' => '0.20s',
                        'icon' =>
                            'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
                    ])

                </div>
            </div>

            {{-- ══════════════════════════════════════════════════════════
                 GRUP 3 — DATA DETAIL
            ═══════════════════════════════════════════════════════════════ --}}
            <div class="mb-10">
                <div class="section-header">
                    <div class="section-bar" style="background:#059669;"></div>
                    <div class="section-meta">
                        <span class="section-badge" style="background:#ecfdf5;color:#059669;">GRUP 3</span>
                        <span class="section-title">Data Detail</span>
                        <span class="section-desc">Bergantung pada Grup 1 &amp; 2 — import terakhir</span>
                    </div>
                    <div class="section-rule"></div>
                    <span class="section-ghost">03</span>
                </div>

                <div class="cards-4">

                    {{-- DATA NILAI --}}
                    @include('import._card', [
                        'title' => 'Data Nilai',
                        'subtitle' => 'Nilai rapor per semester (~105K baris)',
                        'type' => 'data_nilai',
                        'color' => '#059669',
                        'colorBg' => '#ecfdf5',
                        'stepLabel' => '3a',
                        'delay' => '0.05s',
                        'icon' =>
                            'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                        // 'warn' => 'File besar · Proses ~5 menit',
                    ])

                    {{-- DATA NILAI TKA --}}
                    @include('import._card', [
                        'title' => 'Data Nilai TKA',
                        'subtitle' => 'Nilai tes kemampuan akademik',
                        'type' => 'data_nilai_tka',
                        'color' => '#059669',
                        'colorBg' => '#ecfdf5',
                        'stepLabel' => '3b',
                        'delay' => '0.12s',
                        'icon' =>
                            'M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z',
                    ])

                    {{-- DATA PORTOFOLIO --}}
                    @include('import._card', [
                        'title' => 'Data Portofolio',
                        'subtitle' => 'Portofolio siswa per jenis',
                        'type' => 'data_portofolio',
                        'color' => '#d97706',
                        'colorBg' => '#fffbeb',
                        'stepLabel' => '3c',
                        'delay' => '0.20s',
                        'icon' =>
                            'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                    ])

                    {{-- DATA STATUS TAMBAHAN --}}
                    @include('import._card', [
                        'title' => 'Status Tambahan',
                        'subtitle' => 'PKL & status khusus siswa',
                        'type' => 'data_status_tambahan',
                        'color' => '#d97706',
                        'colorBg' => '#fffbeb',
                        'stepLabel' => '3d',
                        'delay' => '0.28s',
                        'icon' =>
                            'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                    ])

                </div>
            </div>

            <p class="mt-4 text-xs text-center mono text-slate-400">
                Async queue · DB transaction · Chunk 500 baris · Upsert untuk tabel referensi
            </p>

        </div>
    </div>
</x-app-layout>
