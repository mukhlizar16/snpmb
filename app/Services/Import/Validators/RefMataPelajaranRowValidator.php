<?php

namespace App\Services\Import\Validators;

class RefMataPelajaranRowValidator
{
    /**
     * CSV column order: 0 kode_mata_pelajaran | 1 nama_mata_pelajaran
     * Tabel tidak memiliki timestamps.
     */
    public function validate(array $row): array
    {
        if (empty($row[0])) {
            throw new \Exception('kode_mata_pelajaran wajib diisi.');
        }

        return [
            'kode_mata_pelajaran' => trim($row[0]),
            'nama_mata_pelajaran' => $row[1] ?: null,
        ];
    }
}
