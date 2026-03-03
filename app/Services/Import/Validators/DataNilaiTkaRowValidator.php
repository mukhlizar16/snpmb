<?php

namespace App\Services\Import\Validators;

class DataNilaiTkaRowValidator
{
    /**
     * CSV column order: 0 nomor_pendaftaran | 1 kode_mata_pelajaran | 2 nilai_tka
     * Tabel tidak memiliki timestamps.
     */
    public function validate(array $row): array
    {
        if (empty($row[0])) {
            throw new \Exception('nomor_pendaftaran wajib diisi.');
        }

        return [
            'nomor_pendaftaran' => (int) $row[0],
            'kode_mata_pelajaran' => $row[1] ?: null,
            'nilai_tka' => isset($row[2]) && $row[2] !== '' ? (float) $row[2] : null,
        ];
    }
}
