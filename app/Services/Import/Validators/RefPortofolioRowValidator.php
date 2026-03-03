<?php

namespace App\Services\Import\Validators;

class RefPortofolioRowValidator
{
    /**
     * CSV column order: 0 kode_portofolio | 1 nama_portofolio | 2 jenis_portofolio
     * Tabel tidak memiliki timestamps.
     */
    public function validate(array $row): array
    {
        if (empty($row[0])) {
            throw new \Exception('kode_portofolio wajib diisi.');
        }

        return [
            'kode_portofolio' => trim($row[0]),
            'nama_portofolio' => $row[1] ?: null,
            'jenis_portofolio' => $row[2] ?: null,
        ];
    }
}
