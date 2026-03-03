<x-app-layout>

    <div style="max-width:80rem;margin:0 auto;padding:36px 1.5rem;">

        {{-- ── Welcome banner ── --}}
        <div style="margin-bottom:32px;">
            <div style="font-size:1.375rem;font-weight:700;color:#0f172a;letter-spacing:-.025em;margin-bottom:4px;">
                Selamat datang, {{ Auth::user()->name }} 👋
            </div>
            <div style="font-size:.875rem;color:#94a3b8;">
                Sistem Manajemen Data SNPMB &mdash; Seleksi Nasional Penerimaan Mahasiswa Baru
            </div>
        </div>

        {{-- ── Quick-access cards ── --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;">

            {{-- Import Data --}}
            <a href="{{ route('import.index') }}"
               style="display:block;background:#fff;border:1.5px solid #e2e8f0;border-radius:14px;
                      padding:24px;text-decoration:none;transition:border-color .15s,box-shadow .15s;"
               onmouseover="this.style.borderColor='#2563eb';this.style.boxShadow='0 4px 16px rgba(37,99,235,.1)';"
               onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';">

                <div style="width:40px;height:40px;background:#eff6ff;border-radius:10px;
                            display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                </div>

                <div style="font-size:.9375rem;font-weight:700;color:#0f172a;margin-bottom:6px;">
                    Import Data
                </div>
                <div style="font-size:.8125rem;color:#94a3b8;line-height:1.55;">
                    Upload file CSV atau Excel untuk memproses data siswa, nilai, pilihan, dan prestasi.
                </div>

                <div style="margin-top:18px;font-size:.75rem;font-weight:600;color:#2563eb;
                            display:flex;align-items:center;gap:4px;">
                    Buka halaman import
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            {{-- Data Siswa --}}
            <a href="{{ route('data.siswa') }}"
               style="display:block;background:#fff;border:1.5px solid #e2e8f0;border-radius:14px;
                      padding:24px;text-decoration:none;transition:border-color .15s,box-shadow .15s;"
               onmouseover="this.style.borderColor='#2563eb';this.style.boxShadow='0 4px 16px rgba(37,99,235,.1)';"
               onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';">

                <div style="width:40px;height:40px;background:#eff6ff;border-radius:10px;
                            display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 10h18M3 14h18M10 6h4M10 18h4M5 6a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>

                <div style="font-size:.9375rem;font-weight:700;color:#0f172a;margin-bottom:6px;">
                    Data Siswa
                </div>
                <div style="font-size:.8125rem;color:#94a3b8;line-height:1.55;">
                    Lihat, cari, dan ekspor seluruh data peserta SNPMB yang telah diimport ke sistem.
                </div>

                <div style="margin-top:18px;font-size:.75rem;font-weight:600;color:#2563eb;
                            display:flex;align-items:center;gap:4px;">
                    Lihat tabel data
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

        </div>

    </div>

</x-app-layout>
