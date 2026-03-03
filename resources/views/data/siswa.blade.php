<x-app-layout>
    <x-slot name="header">
        <span style="font-size:.9375rem;font-weight:700;color:#0f172a;font-family:'IBM Plex Mono',monospace;">
            Data Siswa
        </span>
        <span style="font-size:.75rem;color:#94a3b8;margin-left:8px;">
            Seluruh data siswa yang telah diimport
        </span>
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
        <style>
            .dt-wrapper {
                background: #fff;
                border-radius: 14px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 1px 4px rgba(15, 23, 42, .04);
                overflow: hidden;
            }

            .dt-topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                padding: 16px 20px 12px;
                border-bottom: 1px solid #f1f5f9;
                flex-wrap: wrap;
            }

            .dt-topbar-title {
                font-weight: 700;
                color: #0f172a;
                letter-spacing: .04em;
            }

            .dt-topbar-sub {
                font-size: .6875rem;
                color: #94a3b8;
                margin-top: 2px;
            }

            div.dataTables_wrapper {
                padding: 0;
            }

            div.dataTables_filter {
                display: none;
            }

            div.dataTables_length {
                display: none;
            }

            div.dataTables_info {
                font-size: .7rem;
                color: #64748b;
                padding: 10px 20px;
            }

            div.dataTables_paginate {
                padding: 8px 20px 12px;
            }

            .dataTables_paginate .paginate_button {
                font-size: .75rem !important;
                padding: 4px 9px !important;
                border-radius: 6px !important;
                border: 1px solid #e2e8f0 !important;
                margin: 0 2px !important;
                color: #475569 !important;
            }

            .dataTables_paginate .paginate_button.current {
                background: #2563eb !important;
                border-color: #2563eb !important;
                color: #fff !important;
            }

            .dataTables_paginate .paginate_button:hover:not(.disabled) {
                background: #f1f5f9 !important;
                border-color: #cbd5e1 !important;
                color: #0f172a !important;
            }

            .dataTables_paginate .paginate_button.disabled {
                opacity: .35;
            }

            table.dataTable {
                font-size: .75rem;
                border-collapse: collapse !important;
                width: 100% !important;
            }

            table.dataTable thead th {
                background: #f8fafc;
                color: #475569;
                font-size: .6875rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: .05em;
                padding: 9px 12px;
                border-bottom: 2px solid #e2e8f0 !important;
                white-space: nowrap;
            }

            table.dataTable tbody td {
                padding: 8px 12px;
                color: #334155;
                border-bottom: 1px solid #f1f5f9 !important;
                white-space: nowrap;
                max-width: 200px;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            table.dataTable tbody tr:hover td {
                background: #f8fafc;
            }

            table.dataTable tbody tr:last-child td {
                border-bottom: none !important;
            }

            .badge-yes {
                background: #dcfce7;
                color: #16a34a;
                padding: 2px 7px;
                border-radius: 5px;
                font-size: .65rem;
                font-weight: 600;
            }

            .badge-no {
                background: #f1f5f9;
                color: #94a3b8;
                padding: 2px 7px;
                border-radius: 5px;
                font-size: .65rem;
            }

            .badge-na {
                background: #f1f5f9;
                color: #cbd5e1;
                padding: 2px 7px;
                border-radius: 5px;
                font-size: .65rem;
            }

            .peringkat-badge {
                font-family: 'IBM Plex Mono', monospace;
                font-size: .65rem;
                font-weight: 700;
                color: #2563eb;
                background: #eff6ff;
                padding: 2px 7px;
                border-radius: 5px;
            }

            div.dataTables_processing {
                font-size: .75rem;
                color: #64748b;
                background: rgba(255, 255, 255, .85);
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                padding: 10px 20px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, .06);
            }

            .dt-search-input {
                padding: 6px 10px 6px 32px;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                font-size: .75rem;
                color: #0f172a;
                outline: none;
                width: 220px;
                background: #f8fafc;
                font-family: inherit;
            }

            .dt-search-input:focus {
                border-color: #2563eb;
                background: #fff;
            }

            .dt-search-wrap {
                position: relative;
            }

            .dt-search-wrap svg {
                position: absolute;
                left: 9px;
                top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
                pointer-events: none;
            }

            .dt-length-select {
                padding: 5px 8px;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                font-size: .75rem;
                color: #475569;
                background: #f8fafc;
                cursor: pointer;
            }

            .dt-length-select:focus {
                outline: none;
                border-color: #2563eb;
            }

            .export-wrap {
                position: relative;
                display: inline-block;
            }

            .btn-export {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 6px 14px;
                border-radius: 8px;
                font-size: .75rem;
                font-weight: 600;
                background: #16a34a;
                color: #fff;
                border: none;
                cursor: pointer;
                text-decoration: none;
                transition: background .15s;
                user-select: none;
            }

            .btn-export:hover {
                background: #15803d;
                color: #fff;
            }

            .btn-export.loading {
                background: #86efac;
                cursor: not-allowed;
                pointer-events: none;
            }

            .export-dropdown {
                display: none;
                position: absolute;
                top: calc(100% + 4px);
                right: 0;
                background: #fff;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, .08);
                min-width: 160px;
                z-index: 50;
                overflow: hidden;
            }

            .export-dropdown.open {
                display: block;
            }

            .export-dropdown a {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 9px 14px;
                font-size: .75rem;
                font-weight: 500;
                color: #334155;
                text-decoration: none;
                transition: background .1s;
            }

            .export-dropdown a:hover {
                background: #f1f5f9;
                color: #0f172a;
            }

            .export-dropdown a+a {
                border-top: 1px solid #f1f5f9;
            }
        </style>
    @endpush

    <div style="max-width:80rem;margin:0 auto;padding:24px 1.5rem;">
        <div class="dt-wrapper">

            {{-- Top bar --}}
            <div class="dt-topbar">
                <div>
                    <div class="text-xl dt-topbar-title">Tabel Data Siswa</div>
                    <div class="dt-topbar-sub">Data peserta SNPMB yang telah diimport</div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">

                    {{-- Export dropdown --}}
                    <div class="export-wrap" id="export-wrap">
                        <button type="button" id="btn-export" class="btn-export">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export
                            <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="export-dropdown" id="export-dropdown">
                            <a href="{{ route('data.siswa.export-excel') }}" class="export-item">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#16a34a"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 17v-6l-2 2m0 0l2 2m-2-2h10M4 4h16v4H4z" />
                                    <rect x="3" y="3" width="18" height="18" rx="2" stroke="#16a34a"
                                        stroke-width="1.5" fill="none" />
                                    <path stroke-linecap="round" stroke-width="1.5" d="M8 8l3 4-3 4M13 16h3" />
                                </svg>
                                Excel (.xlsx)
                            </a>
                            <a href="{{ route('data.siswa.export') }}" class="export-item">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#64748b"
                                    stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                    <path stroke-linecap="round" d="M8 7h8M8 12h8M8 17h5" />
                                </svg>
                                CSV (.csv)
                            </a>
                        </div>
                    </div>

                    <div style="display:flex;align-items:center;gap:6px;">
                        <span style="font-size:.7rem;color:#94a3b8;">Tampilkan</span>
                        <select class="dt-length-select" id="dt-length">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="250">250</option>
                            <option value="500">500</option>
                        </select>
                        {{-- <span style="font-size:.7rem;color:#94a3b8;">data</span> --}}
                    </div>

                    <div class="dt-search-wrap">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                        </svg>
                        <input type="text" id="dt-search" class="dt-search-input"
                            placeholder="Cari nama, NIK, NISN, sekolah…">
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div style="overflow-x:auto;">
                <table id="data-siswa-table" class="dataTable" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Peringkat</th>
                            <th>No. Pendaftaran</th>
                            <th>Nama Siswa</th>
                            <th>File Foto</th>
                            <th>ID Jurusan</th>
                            <th>Jurusan</th>
                            <th>NPSN Sekolah</th>
                            <th>Jenis Kelamin</th>
                            <th>Tgl Lahir</th>
                            <th>NISN</th>
                            <th>NIK</th>
                            <th>Jml Nilai &lt; KKM</th>
                            <th>Siswa Pindahan</th>
                            <th>Pertukaran Pelajar</th>
                            <th>Siswa Cuti</th>
                            <th>Ranking Sekolah</th>
                            <th>Jml Siswa Jurusan</th>
                            <th>Rata Nilai Rapor</th>
                            <th>Rata Rapor (tanpa sem. akhir)</th>
                            <th>Rata Rapor Tambahan</th>
                            <th>KIP/KKS</th>
                            <th>Kategori KIP</th>
                            <th>Tipe Studi</th>
                            <th>Jml Tanggungan</th>
                            <th>Penghasilan Ayah</th>
                            <th>Penghasilan Ibu</th>
                            <th>Kebutuhan Khusus</th>
                            <th>Nama Sekolah</th>
                            <th>Jurusan SMA</th>
                            <th>MPP</th>
                            <th>Index SMA</th>
                            <th>Nilai Rata-Rata MP</th>
                            <th>Nilai MP Pendukung</th>
                            <th>NAS</th>
                            <th>Nilai Prestasi</th>
                            <th>Nilai Akhir</th>
                        </tr>
                    </thead>
                </table>
            </div>

            {{-- Footer --}}
            <div
                style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;padding:0 20px 4px;gap:8px;">
                <div id="dt-info" style="font-size:.7rem;color:#94a3b8;"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
        <script>
            $(function() {
                const table = $('#data-siswa-table').DataTable({
                    serverSide: true,
                    processing: true,
                    ajax: {
                        url: '{{ route('data.siswa.json') }}',
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            render: (d, t, r, meta) =>
                                `<span class="peringkat-badge">${meta.settings._iDisplayStart + meta.row + 1}</span>`,
                            searchable: false
                        },
                        {
                            data: 'nomor_pendaftaran',
                            name: 'ds.nomor_pendaftaran'
                        },
                        {
                            data: 'nama_siswa',
                            name: 'ds.nama_siswa'
                        },
                        {
                            data: 'file_foto',
                            name: 'ds.file_foto',
                            orderable: false,
                            render: d => d ?
                                `<a href="${d}" target="_blank" style="color:#2563eb;text-decoration:underline;font-size:.7rem;">Lihat</a>` :
                                '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'id_jurusan',
                            orderable: false,
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            name: 'nama_jurusan',
                            data: 'nama_jurusan',
                            orderable: false,
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'npsn_sekolah',
                            name: 'ds.npsn_sekolah',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'kode_jenis_kelamin',
                            name: 'ds.kode_jenis_kelamin',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'tanggal_lahir',
                            name: 'ds.tanggal_lahir',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'nisn',
                            name: 'ds.nisn',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'nik',
                            name: 'ds.nik',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'jumlah_nilai_di_bawah_kkm',
                            orderable: false,
                            render: d => d > 0 ?
                                `<span style="color:#ef4444;font-weight:600;">${d}</span>` : (d ??
                                    '<span class="badge-na">—</span>')
                        },
                        {
                            data: 'status_siswa_pindahan',
                            name: 'ds.status_siswa_pindahan',
                            render: d => d ? '<span class="badge-yes">Ya</span>' :
                                '<span class="badge-no">Tidak</span>'
                        },
                        {
                            data: 'status_siswa_pertukaran_pelajar',
                            name: 'ds.status_siswa_pertukaran_pelajar',
                            render: d => d ? '<span class="badge-yes">Ya</span>' :
                                '<span class="badge-no">Tidak</span>'
                        },
                        {
                            data: 'status_siswa_cuti',
                            name: 'ds.status_siswa_cuti',
                            render: d => d ? '<span class="badge-yes">Ya</span>' :
                                '<span class="badge-no">Tidak</span>'
                        },
                        {
                            data: 'ranking_versi_sekolah',
                            name: 'ds.ranking_versi_sekolah',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'jumlah_siswa_jurusan',
                            name: 'ds.jumlah_siswa_jurusan',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'rata_nilai_rapor',
                            name: 'ds.rata_nilai_rapor',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'rata_nilai_rapor_tanpa_sem_akhir',
                            name: 'ds.rata_nilai_rapor_tanpa_sem_akhir',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'rata_nilai_rapor_tambahan',
                            name: 'ds.rata_nilai_rapor_tambahan',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'kip_kks',
                            name: 'ds.kip_k',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'kategori_kip',
                            name: 'ds.kategori_kip',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'tipe_studi',
                            name: 'ds.tipe_studi',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'jumlah_tanggungan',
                            name: 'ds.jumlah_tanggungan',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'penghasilan_ayah',
                            name: 'ds.penghasilan_ayah',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'penghasilan_ibu',
                            name: 'ds.penghasilan_ibu',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'kebutuhan_khusus',
                            name: 'ds.kebutuhan_khusus',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'nama_sekolah',
                            name: 'sk.nama_sekolah',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'jurusan_sma',
                            orderable: false,
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'mpp',
                            orderable: false,
                            render: d => d ?? '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'index_sma',
                            name: 'ds.rata_nilai_rapor_adjusted',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'rata_nilai_mp',
                            orderable: false,
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'nilai_mp_pendukung',
                            orderable: false,
                            render: d => d ?? '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'nas',
                            orderable: false,
                            render: d => d ?? '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'nilai_prestasi',
                            name: 'dp.nilai_prestasi',
                            defaultContent: '<span class="badge-na">—</span>'
                        },
                        {
                            data: 'nilai_akhir',
                            orderable: false,
                            render: d => d ?? '<span class="badge-na">—</span>'
                        },
                    ],
                    scrollX: true,
                    language: {
                        processing: 'Memuat data…',
                        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                        infoEmpty: 'Tidak ada data',
                        infoFiltered: '(difilter dari _MAX_ total)',
                        paginate: {
                            first: '«',
                            previous: '‹',
                            next: '›',
                            last: '»'
                        },
                        emptyTable: 'Belum ada data siswa yang diimport',
                    },
                });

                // Custom search
                let searchTimer;
                $('#dt-search').on('keyup', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => table.search(this.value).draw(), 400);
                });

                // Custom length
                $('#dt-length').on('change', function() {
                    table.page.len(+this.value).draw();
                });

                // Info text sync
                table.on('draw', function() {
                    const info = document.querySelector('.dataTables_info');
                    if (info) $('#dt-info').html(info.innerHTML);
                });

                // Export dropdown toggle
                $('#btn-export').on('click', function(e) {
                    e.stopPropagation();
                    $('#export-dropdown').toggleClass('open');
                });

                // Tutup dropdown jika klik di luar
                $(document).on('click', function() {
                    $('#export-dropdown').removeClass('open');
                });

                // Loading state saat export item diklik
                $('.export-item').on('click', function() {
                    $('#export-dropdown').removeClass('open');
                    const btn = $('#btn-export');
                    btn.addClass('loading').html('Menyiapkan…');
                    setTimeout(() => btn.removeClass('loading').html(
                        '<svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg> Export ' +
                        '<svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>'
                    ), 3000);
                });
            });
        </script>
    @endpush
</x-app-layout>
