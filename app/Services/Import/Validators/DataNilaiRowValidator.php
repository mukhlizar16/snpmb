<?php

namespace App\Services\Import\Validators;

class DataNilaiRowValidator
{
    /**
     * CSV column order (positional):
     * 0  nomor_pendaftaran    | 1  semester           | 2  kode_mata_pelajaran
     * 3  wajib (TRUE/FALSE)   | 4  tingkat            | 5  nilai
     * 6  nilai_validasi_tka   | 7  tahun_kur          | 8  unit
     * 9  kkm                  | 10 asal_sekolah
     */
    public function validate(array $row): array
    {
        if (empty($row[0])) {
            throw new \Exception('nomor_pendaftaran wajib diisi.');
        }

        return [
            'nomor_pendaftaran' => (int) $row[0],
            'semester' => isset($row[1]) && $row[1] !== '' ? (int) $row[1] : null,
            'kode_mata_pelajaran' => $row[2] ?: null,
            'wajib' => strtoupper((string) ($row[3] ?? '')) === 'TRUE',
            'tingkat' => isset($row[4]) && $row[4] !== '' ? (int) $row[4] : null,
            'nilai' => isset($row[5]) && $row[5] !== '' ? (float) $row[5] : null,
            'nilai_validasi_tka' => isset($row[6]) && $row[6] !== '' ? (float) $row[6] : null,
            'tahun_kur' => isset($row[7]) && $row[7] !== '' ? (int) $row[7] : null,
            'unit' => $row[8] ?: null,
            'kkm' => isset($row[9]) && $row[9] !== '' ? (float) $row[9] : null,
            'asal_sekolah' => $row[10] ?: null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
