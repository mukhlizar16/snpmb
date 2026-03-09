<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DataSiswaProdiSheet extends DefaultValueBinder implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithColumnFormatting,
    WithEvents,
    WithCustomValueBinder,
    WithTitle
{
    private int $no = 0;

    private const TEXT_COLUMNS = ['B', 'G', 'J', 'K'];

    public function __construct(
        private readonly string $kodeProdi,
        private readonly string $namaProdi,
        private readonly int $noUrut = 1,
    ) {}

    public function title(): string
    {
        // Hapus karakter tidak valid untuk nama sheet Excel, batasi 31 karakter
        $name   = preg_replace('/[\\\\\/\*\?\[\]:]/', '', $this->namaProdi);
        $suffix = ' P' . $this->noUrut;
        $base   = mb_substr($name ?: $this->kodeProdi, 0, 31 - mb_strlen($suffix));

        return $base . $suffix;
    }

    public function query()
    {
        $u = $this->noUrut; // urutan aktif untuk subquery tampilan

        return DB::table('data_siswa as ds')
            ->leftJoin('data_sekolah as sk', 'sk.npsn', '=', 'ds.npsn_sekolah')
            ->leftJoin('ref_jurusan as rj', 'rj.id_jurusan', '=', 'ds.id_jurusan')
            ->leftJoin('ref_index_sekolah as ris', 'ris.npsn', '=', 'ds.npsn_sekolah')
            ->leftJoin(
                DB::raw('(SELECT nomor_pendaftaran, MAX(nilai_prestasi) as nilai_prestasi FROM data_prestasi GROUP BY nomor_pendaftaran) as dp'),
                'dp.nomor_pendaftaran', '=', 'ds.nomor_pendaftaran'
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
                COALESCE(dp.nilai_prestasi, 0) as nilai_prestasi,
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
            ->whereRaw('EXISTS (SELECT 1 FROM data_pilihan WHERE nomor_pendaftaran = ds.nomor_pendaftaran AND no_urut_pilihan = ? AND kode_prodi = ?)', [$this->noUrut, $this->kodeProdi])
            ->orderByRaw('nilai_akhir DESC');
    }

    public function headings(): array
    {
        return [
            'No',                              // A
            'No. Pendaftaran',                 // B
            'Nama Siswa',                      // C
            'File Foto',                       // D
            'ID Jurusan',                      // E
            'Jurusan',                         // F
            'NPSN Sekolah',                    // G
            'Jenis Kelamin',                   // H
            'Tgl Lahir',                       // I
            'NISN',                            // J
            'NIK',                             // K
            'Kode Prodi Pilihan 1',            // L
            'Kode Prodi Pilihan 2',            // M
            'Status Pindahan',                 // N
            'Pertukaran Pelajar',              // O
            'Siswa Cuti',                      // P
            'Ranking Sekolah',                 // Q
            'Jml Siswa Jurusan',               // R
            'Rata Nilai Rapor',                // S
            'Rata Rapor (tanpa sem. akhir)',   // T
            'Rata Rapor Tambahan',             // U
            'KIP/KKS',                         // V
            'Kategori KIP',                    // W
            'Tipe Studi',                      // X
            'Jml Tanggungan',                  // Y
            'Penghasilan Ayah',                // Z
            'Penghasilan Ibu',                 // AA
            'Kebutuhan Khusus',                // AB
            'Nama Sekolah',                    // AC
            'Jurusan SMA/SMK',                 // AD
            'MP Pendukung',                    // AE
            'Index SMA',                       // AF
            'Nilai Prestasi',                  // AG
            'Nilai MP Pendukung',              // AH
            'NAS',                             // AI
            'Nilai Akhir',                     // AJ
        ];
    }

    public function map($row): array
    {
        return [
            ++$this->no,                                              // A
            (string) $row->nomor_pendaftaran,                         // B — text
            $row->nama_siswa,                                         // C
            $row->file_foto,                                          // D
            $row->id_jurusan,                                         // E
            $row->nama_jurusan,                                       // F
            (string) $row->npsn_sekolah,                             // G — text
            $row->kode_jenis_kelamin,                                 // H
            $row->tanggal_lahir,                                      // I
            (string) $row->nisn,                                      // J — text
            (string) $row->nik,                                       // K — text (16 digit)
            $row->kode_prodi_pilihan_1,                              // L
            $row->kode_prodi_pilihan_2,                              // M
            $row->status_siswa_pindahan ? 'Ya' : 'Tidak',            // N
            $row->status_siswa_pertukaran_pelajar ? 'Ya' : 'Tidak',  // O
            $row->status_siswa_cuti ? 'Ya' : 'Tidak',                // P
            $row->ranking_versi_sekolah,                              // Q
            $row->jumlah_siswa_jurusan,                              // R
            $row->rata_nilai_rapor,                                  // S
            $row->rata_nilai_rapor_tanpa_sem_akhir,                  // T
            $row->rata_nilai_rapor_tambahan,                          // U
            $row->kip_k,                                              // V
            $row->kategori_kip,                                      // W
            $row->tipe_studi,                                         // X
            $row->jumlah_tanggungan,                                 // Y
            $row->penghasilan_ayah,                                  // Z
            $row->penghasilan_ibu,                                   // AA
            $row->kebutuhan_khusus,                                  // AB
            $row->nama_sekolah,                                      // AC
            $row->jurusan_sma,                                       // AD
            $row->mpp,                                               // AE
            $row->index_sma,                                         // AF
            $row->nilai_prestasi,                                    // AG
            $row->nilai_mp_pendukung,                                // AH
            $row->nas,                                               // AI
            $row->nilai_akhir,                                       // AJ
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B'  => NumberFormat::FORMAT_TEXT,
            'G'  => NumberFormat::FORMAT_TEXT,
            'J'  => NumberFormat::FORMAT_TEXT,
            'K'  => NumberFormat::FORMAT_TEXT,
            'S'  => '0.00',
            'T'  => '0.00',
            'U'  => '0.00',
            'AF' => '0.00',
            'AG' => '0.00',
            'AH' => '0.00',
            'AI' => '0.00',
            'AJ' => '0.00',
        ];
    }

    public function bindValue(Cell $cell, $value): bool
    {
        if (\in_array($cell->getColumn(), self::TEXT_COLUMNS) && $value !== null && $value !== '') {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastCol = $sheet->getHighestColumn();
                $range = "A1:{$lastCol}{$lastRow}";

                $sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFD1D5DB'],
                        ],
                    ],
                ]);

                $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['argb' => 'FF94A3B8'],
                        ],
                    ],
                ]);

                $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF1F5F9'],
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FF475569'],
                        'size' => 10,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(32);
                $sheet->freezePane('A2');
            },
        ];
    }
}
