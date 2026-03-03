<?php

namespace App\Services\Import\Validators;

class RefJurusanRowValidator
{
    /**
     * CSV column order: 0 id_jurusan | 1 nama_jurusan
     * Tabel tidak memiliki timestamps.
     */
    public function validate(array $row): array
    {
        if (empty($row[0])) {
            throw new \Exception('id_jurusan wajib diisi.');
        }

        return [
            'id_jurusan' => trim($row[0]),
            'nama_jurusan' => $row[1] ?: null,
        ];
    }
}
