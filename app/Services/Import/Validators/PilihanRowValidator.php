<?php

namespace App\Services\Import\Validators;

class PilihanRowValidator
{
    /**
     * CSV column order (positional):
     * 0 kode_prodi | 1 nomor_pendaftaran | 2 no_urut_pilihan
     */
    public function validate(array $row): array
    {
        if (empty($row[1])) {
            throw new \Exception('nomor_pendaftaran wajib diisi.');
        }

        return [
            'kode_prodi' => $row[0] ?: null,
            'nomor_pendaftaran' => (int) $row[1],
            'no_urut_pilihan' => isset($row[2]) && $row[2] !== '' ? (int) $row[2] : null,
        ];
    }
}
