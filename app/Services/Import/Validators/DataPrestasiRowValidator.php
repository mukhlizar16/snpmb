<?php

namespace App\Services\Import\Validators;

class DataPrestasiRowValidator
{
    /**
     * CSV column order (positional):
     * 0 nomor_pendaftaran  | 1 no_prestasi          | 2 jenis_prestasi
     * 3 jenjang_prestasi   | 4 individu_berkelompok  | 5 lembaga_pemberi_prestasi
     * 6 tahun              | 7 nilai_prestasi        | 8 deskripsi
     * 9 file_sertifikat
     */
    public function validate(array $row): array
    {
        if (empty($row[0])) {
            throw new \Exception('nomor_pendaftaran wajib diisi.');
        }

        return [
            'nomor_pendaftaran' => (int) $row[0],
            'no_prestasi' => isset($row[1]) && $row[1] !== '' ? (int) $row[1] : null,
            'jenis_prestasi' => $row[2] ?: null,
            'jenjang_prestasi' => $row[3] ?: null,
            'individu_berkelompok' => $row[4] ?: null,
            'lembaga_pemberi_prestasi' => $row[5] ?: null,
            'tahun' => isset($row[6]) && $row[6] !== '' ? (int) $row[6] : null,
            'nilai_prestasi' => isset($row[7]) && $row[7] !== '' ? (float) $row[7] : null,
            'deskripsi' => $row[8] ?: null,
            'file_sertifikat' => $row[9] ?: null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
