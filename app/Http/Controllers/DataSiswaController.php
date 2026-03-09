<?php

namespace App\Http\Controllers;

use App\Exports\DataSiswaExport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;

class DataSiswaController extends Controller
{
    public function index()
    {
        return view('data.siswa');
    }

    public function json(Request $request): JsonResponse
    {
        $noUrut    = (int) $request->input('no_urut_pilihan', 0);
        $kodeProdi = trim($request->input('kode_prodi', ''));

        $query = $this->baseQuery($noUrut);

        if ($noUrut > 0 && $kodeProdi !== '') {
            $query->whereRaw(
                'EXISTS (SELECT 1 FROM data_pilihan WHERE nomor_pendaftaran = ds.nomor_pendaftaran AND no_urut_pilihan = ? AND kode_prodi = ?)',
                [$noUrut, $kodeProdi]
            );
        }

        return DataTables::of($query)
            ->filter(function ($q) use ($request) {
                $search = trim($request->input('search.value', ''));
                if ($search !== '') {
                    $q->where(function ($w) use ($search) {
                        $w->where('ds.nomor_pendaftaran', 'like', "%{$search}%")
                            ->orWhere('ds.nama_siswa', 'like', "%{$search}%")
                            ->orWhere('ds.nisn', 'like', "%{$search}%")
                            ->orWhere('ds.nik', 'like', "%{$search}%")
                            ->orWhere('sk.nama_sekolah', 'like', "%{$search}%");
                    });
                }
            })
            ->make(true);
    }

    public function prodiOptions(Request $request): JsonResponse
    {
        $noUrut = (int) $request->input('no_urut_pilihan', 1);

        $rows = DB::table('data_pilihan as dp')
            ->leftJoin('data_prodi as prd', 'prd.kode_prodi', '=', 'dp.kode_prodi')
            ->select('dp.kode_prodi', 'prd.nama_prodi')
            ->where('dp.no_urut_pilihan', $noUrut)
            ->distinct()
            ->orderBy('dp.kode_prodi')
            ->get();

        return response()->json($rows);
    }

    public function export(Request $request): StreamedResponse
    {
        $noUrut    = (int) $request->input('no_urut_pilihan', 0);
        $kodeProdi = trim($request->input('kode_prodi', ''));

        $filename = 'data-siswa-' . now()->format('YmdHis') . '.csv';

        $headers = [
            'No',
            'No. Pendaftaran',
            'Nama Siswa',
            'File Foto',
            'ID Jurusan',
            'Jurusan',
            'NPSN Sekolah',
            'Jenis Kelamin',
            'Tgl Lahir',
            'NISN',
            'NIK',
            'Kode Prodi Pilihan 1',
            'Kode Prodi Pilihan 2',
            'Status Pindahan',
            'Pertukaran Pelajar',
            'Siswa Cuti',
            'Ranking Sekolah',
            'Jml Siswa Jurusan',
            'Rata Nilai Rapor',
            'Rata Rapor (tanpa sem. akhir)',
            'Rata Rapor Tambahan',
            'KIP/KKS',
            'Kategori KIP',
            'Tipe Studi',
            'Jml Tanggungan',
            'Penghasilan Ayah',
            'Penghasilan Ibu',
            'Kebutuhan Khusus',
            'Nama Sekolah',
            'Jurusan SMA/SMK',
            'MP Pendukung',
            'Index SMA',
            'Nilai Prestasi',
        ];

        return response()->streamDownload(function () use ($headers, $noUrut, $kodeProdi) {
            $handle = fopen('php://output', 'w');

            // BOM agar Excel baca UTF-8 dengan benar
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers);

            $no = 1;
            $u  = $noUrut ?: 1; // urutan aktif untuk subquery tampilan

            // Gunakan chunk(500) agar tidak habis memori
            DB::table('data_siswa as ds')
                ->leftJoin('data_sekolah as sk', 'sk.npsn', '=', 'ds.npsn_sekolah')
                ->leftJoin('ref_jurusan as rj', 'rj.id_jurusan', '=', 'ds.id_jurusan')
                ->leftJoin('ref_index_sekolah as ris', 'ris.npsn', '=', 'ds.npsn_sekolah')
                ->leftJoin(
                    DB::raw('(SELECT nomor_pendaftaran, MAX(nilai_prestasi) as nilai_prestasi FROM data_prestasi GROUP BY nomor_pendaftaran) as dp'),
                    'dp.nomor_pendaftaran',
                    '=',
                    'ds.nomor_pendaftaran'
                )
                ->selectRaw("
                    ds.nomor_pendaftaran, ds.nama_siswa, ds.file_foto,
                    (SELECT kode_prodi FROM data_pilihan
                        WHERE nomor_pendaftaran = ds.nomor_pendaftaran AND no_urut_pilihan = {$u}
                        LIMIT 1) as id_jurusan,
                    (SELECT prd.nama_prodi FROM data_pilihan dpl
                        INNER JOIN data_prodi prd ON prd.kode_prodi = dpl.kode_prodi
                        WHERE dpl.nomor_pendaftaran = ds.nomor_pendaftaran AND dpl.no_urut_pilihan = {$u}
                        LIMIT 1) as nama_jurusan,
                    ds.npsn_sekolah,
                    ds.kode_jenis_kelamin, ds.tanggal_lahir, ds.nisn, ds.nik,
                    (SELECT kode_prodi FROM data_pilihan
                        WHERE nomor_pendaftaran = ds.nomor_pendaftaran AND no_urut_pilihan = {$u}
                        LIMIT 1) as kode_prodi_pilihan_1,
                    (SELECT kode_prodi FROM data_pilihan
                        WHERE nomor_pendaftaran = ds.nomor_pendaftaran AND no_urut_pilihan = 2
                        LIMIT 1) as kode_prodi_pilihan_2,
                    ds.status_siswa_pindahan, ds.status_siswa_pertukaran_pelajar, ds.status_siswa_cuti,
                    ds.ranking_versi_sekolah, ds.jumlah_siswa_jurusan,
                    ds.rata_nilai_rapor, ds.rata_nilai_rapor_tanpa_sem_akhir, ds.rata_nilai_rapor_tambahan,
                    ds.kip_k, ds.kategori_kip, ds.tipe_studi, ds.jumlah_tanggungan,
                    ds.penghasilan_ayah, ds.penghasilan_ibu, ds.kebutuhan_khusus,
                    sk.nama_sekolah,
                    rj.nama_jurusan as jurusan_sma,
                    (SELECT GROUP_CONCAT(rmp.nama_mata_pelajaran ORDER BY rmp.nama_mata_pelajaran SEPARATOR ', ')
                        FROM ref_mp_pendukung rmpp
                        INNER JOIN ref_mata_pelajaran rmp ON rmp.kode_mata_pelajaran = rmpp.kode_mata_pelajaran
                        WHERE rmpp.id_jurusan = ds.id_jurusan
                          AND rmpp.kode_prodi = (
                              SELECT kode_prodi FROM data_pilihan
                              WHERE nomor_pendaftaran = ds.nomor_pendaftaran AND no_urut_pilihan = {$u}
                              LIMIT 1
                          )
                    ) as mpp,
                    ris.index_sekolah as index_sma,
                    COALESCE(dp.nilai_prestasi, 0) as nilai_prestasi
                ")
                ->when($noUrut > 0, fn ($q) => $q->whereRaw(
                    'EXISTS (SELECT 1 FROM data_pilihan WHERE nomor_pendaftaran = ds.nomor_pendaftaran AND no_urut_pilihan = ?)',
                    [$noUrut]
                ))
                ->when($noUrut > 0 && $kodeProdi !== '', fn ($q) => $q->whereRaw(
                    'EXISTS (SELECT 1 FROM data_pilihan WHERE nomor_pendaftaran = ds.nomor_pendaftaran AND no_urut_pilihan = ? AND kode_prodi = ?)',
                    [$noUrut, $kodeProdi]
                ))
                ->orderBy('ds.nomor_pendaftaran')
                ->chunk(500, function ($rows) use ($handle, &$no) {
                    foreach ($rows as $row) {
                        fputcsv($handle, [
                            $no++,
                            $row->nomor_pendaftaran,
                            $row->nama_siswa,
                            $row->file_foto,
                            $row->id_jurusan,
                            $row->nama_jurusan,
                            $row->npsn_sekolah,
                            $row->kode_jenis_kelamin,
                            $row->tanggal_lahir,
                            $this->csvText($row->nisn),
                            $this->csvText($row->nik),
                            $row->kode_prodi_pilihan_1,
                            $row->kode_prodi_pilihan_2,
                            $row->status_siswa_pindahan ? 'Ya' : 'Tidak',
                            $row->status_siswa_pertukaran_pelajar ? 'Ya' : 'Tidak',
                            $row->status_siswa_cuti ? 'Ya' : 'Tidak',
                            $row->ranking_versi_sekolah,
                            $row->jumlah_siswa_jurusan,
                            $this->csvDecimal($row->rata_nilai_rapor),
                            $this->csvDecimal($row->rata_nilai_rapor_tanpa_sem_akhir),
                            $this->csvDecimal($row->rata_nilai_rapor_tambahan),
                            $row->kip_k,
                            $row->kategori_kip,
                            $row->tipe_studi,
                            $row->jumlah_tanggungan,
                            $row->penghasilan_ayah,
                            $row->penghasilan_ibu,
                            $row->kebutuhan_khusus,
                            $row->nama_sekolah,
                            $row->jurusan_sma,
                            $row->mpp,
                            $this->csvDecimal($row->index_sma),
                            $this->csvDecimal($row->nilai_prestasi),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportExcel(Request $request): BinaryFileResponse
    {
        $noUrut    = (int) $request->input('no_urut_pilihan', 0);
        $kodeProdi = trim($request->input('kode_prodi', ''));

        $filename = 'data-siswa-' . now()->format('YmdHis') . '.xlsx';

        return Excel::download(new DataSiswaExport($noUrut, $kodeProdi), $filename);
    }

    // ── CSV format helpers ──────────────────────────────────────────────────────

    /**
     * Paksa Excel baca sebagai teks agar NIK/NISN tidak kehilangan digit.
     * Menggunakan formula ="value" sehingga Excel menampilkan string asli.
     */
    private function csvText($value): string
    {
        if ($value === null || $value === '') {
            return '';
        }
        return "=\"{$value}\"";
    }

    /**
     * Ganti titik desimal dengan koma agar Excel locale Indonesia
     * tidak salah baca (misal: 80.78 → 8078).
     */
    private function csvDecimal($value): string
    {
        if ($value === null || $value === '') {
            return '';
        }
        return str_replace('.', ',', (string) $value);
    }

    // ── Query bersama untuk json() ──────────────────────────────────────────────

    private function baseQuery(int $noUrut = 0)
    {
        $u = $noUrut ?: 1; // urutan aktif untuk subquery tampilan

        return DB::table('data_siswa as ds')
            ->leftJoin('data_sekolah as sk', 'sk.npsn', '=', 'ds.npsn_sekolah')
            ->leftJoin('ref_jurusan as rj', 'rj.id_jurusan', '=', 'ds.id_jurusan')
            ->leftJoin('ref_index_sekolah as ris', 'ris.npsn', '=', 'ds.npsn_sekolah')
            ->leftJoin(
                DB::raw('(SELECT nomor_pendaftaran, MAX(nilai_prestasi) as nilai_prestasi FROM data_prestasi GROUP BY nomor_pendaftaran) as dp'),
                'dp.nomor_pendaftaran',
                '=',
                'ds.nomor_pendaftaran'
            )
            ->selectRaw("
                ds.nomor_pendaftaran,
                ds.nama_siswa,
                ds.file_foto,
                (SELECT kode_prodi FROM data_pilihan
                    WHERE nomor_pendaftaran = ds.nomor_pendaftaran AND no_urut_pilihan = {$u}
                    LIMIT 1) as id_jurusan,
                (SELECT prd.nama_prodi FROM data_pilihan dpl
                    INNER JOIN data_prodi prd ON prd.kode_prodi = dpl.kode_prodi
                    WHERE dpl.nomor_pendaftaran = ds.nomor_pendaftaran AND dpl.no_urut_pilihan = {$u}
                    LIMIT 1) as nama_jurusan,
                ds.npsn_sekolah,
                ds.kode_jenis_kelamin,
                ds.tanggal_lahir,
                ds.nisn,
                ds.nik,
                (SELECT COUNT(*) FROM data_nilai dn
                    WHERE dn.nomor_pendaftaran = ds.nomor_pendaftaran
                      AND dn.nilai IS NOT NULL AND dn.kkm IS NOT NULL
                      AND dn.nilai < dn.kkm
                ) as jumlah_nilai_di_bawah_kkm,
                ds.status_siswa_pindahan,
                ds.status_siswa_pertukaran_pelajar,
                ds.status_siswa_cuti,
                ds.ranking_versi_sekolah,
                ds.jumlah_siswa_jurusan,
                ds.rata_nilai_rapor,
                ds.rata_nilai_rapor_tanpa_sem_akhir,
                ds.rata_nilai_rapor_tambahan,
                ds.kip_k          as kip_kks,
                ds.kategori_kip,
                ds.tipe_studi,
                ds.jumlah_tanggungan,
                ds.penghasilan_ayah,
                ds.penghasilan_ibu,
                ds.kebutuhan_khusus,
                sk.nama_sekolah,
                rj.nama_jurusan   as jurusan_sma,
                (SELECT GROUP_CONCAT(rmp.nama_mata_pelajaran ORDER BY rmp.nama_mata_pelajaran SEPARATOR ', ')
                    FROM ref_mp_pendukung rmpp
                    INNER JOIN ref_mata_pelajaran rmp ON rmp.kode_mata_pelajaran = rmpp.kode_mata_pelajaran
                    WHERE rmpp.id_jurusan = ds.id_jurusan
                      AND rmpp.kode_prodi = (
                          SELECT kode_prodi FROM data_pilihan
                          WHERE nomor_pendaftaran = ds.nomor_pendaftaran AND no_urut_pilihan = {$u}
                          LIMIT 1
                      )
                ) as mpp,
                ris.index_sekolah as index_sma,
                (SELECT ROUND(AVG(dnt.nilai_tka), 2) FROM data_nilai_tka dnt
                    WHERE dnt.nomor_pendaftaran = ds.nomor_pendaftaran
                      AND dnt.nilai_tka IS NOT NULL
                ) as rata_nilai_mp,
                (SELECT dn.nilai_validasi_tka
                    FROM ref_mp_pendukung rmpp
                    INNER JOIN data_nilai dn ON dn.nomor_pendaftaran = ds.nomor_pendaftaran
                        AND dn.kode_mata_pelajaran = rmpp.kode_mata_pelajaran
                    WHERE rmpp.id_jurusan = ds.id_jurusan
                      AND rmpp.kode_prodi = (
                          SELECT kode_prodi FROM data_pilihan
                          WHERE nomor_pendaftaran = ds.nomor_pendaftaran AND no_urut_pilihan = {$u}
                          LIMIT 1
                      )
                    LIMIT 1
                ) as nilai_mp_pendukung,
                ROUND(
                    0.5 * COALESCE(
                        (SELECT ROUND(AVG(dnt.nilai_tka), 2) FROM data_nilai_tka dnt
                            WHERE dnt.nomor_pendaftaran = ds.nomor_pendaftaran
                              AND dnt.nilai_tka IS NOT NULL),
                        0
                    ) +
                    0.5 * COALESCE(
                        (SELECT dn2.nilai_validasi_tka
                            FROM ref_mp_pendukung rmpp2
                            INNER JOIN data_nilai dn2 ON dn2.nomor_pendaftaran = ds.nomor_pendaftaran
                                AND dn2.kode_mata_pelajaran = rmpp2.kode_mata_pelajaran
                            WHERE rmpp2.id_jurusan = ds.id_jurusan
                              AND rmpp2.kode_prodi = (
                                  SELECT kode_prodi FROM data_pilihan
                                  WHERE nomor_pendaftaran = ds.nomor_pendaftaran AND no_urut_pilihan = {$u}
                                  LIMIT 1
                              )
                            LIMIT 1),
                        0
                    ),
                    2
                ) as nas,
                COALESCE(dp.nilai_prestasi, 0) as nilai_prestasi,
                ROUND(
                    ROUND(
                        0.5 * COALESCE(
                            (SELECT ROUND(AVG(dnt.nilai_tka), 2) FROM data_nilai_tka dnt
                                WHERE dnt.nomor_pendaftaran = ds.nomor_pendaftaran
                                  AND dnt.nilai_tka IS NOT NULL),
                            0
                        ) +
                        0.5 * COALESCE(
                            (SELECT dn2.nilai_validasi_tka
                                FROM ref_mp_pendukung rmpp2
                                INNER JOIN data_nilai dn2 ON dn2.nomor_pendaftaran = ds.nomor_pendaftaran
                                    AND dn2.kode_mata_pelajaran = rmpp2.kode_mata_pelajaran
                                WHERE rmpp2.id_jurusan = ds.id_jurusan
                                  AND rmpp2.kode_prodi = (
                                      SELECT kode_prodi FROM data_pilihan
                                      WHERE nomor_pendaftaran = ds.nomor_pendaftaran AND no_urut_pilihan = {$u}
                                      LIMIT 1
                                  )
                                LIMIT 1),
                            0
                        ),
                        2
                    ) * COALESCE(ris.index_sekolah, 0) +
                    0.05 * COALESCE(dp.nilai_prestasi, 0),
                    2
                ) as nilai_akhir
            ")
            ->when($noUrut > 0, fn ($q) => $q->whereRaw(
                'EXISTS (SELECT 1 FROM data_pilihan WHERE nomor_pendaftaran = ds.nomor_pendaftaran AND no_urut_pilihan = ?)',
                [$noUrut]
            ));
    }
}
