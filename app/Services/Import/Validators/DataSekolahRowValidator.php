<?php

namespace App\Services\Import\Validators;

use Carbon\Carbon;

class DataSekolahRowValidator
{
    /**
     * CSV column order (positional):
     * 0  npsn                    | 1  nama_sekolah
     * 2  jenis_sekolah           | 3  kode_kabupaten
     * 4  nama_kabupaten          | 5  kode_provinsi
     * 6  nama_provinsi           | 7  akreditasi_sekolah
     * 8  tanggal_mulai_akreditasi| 9  tanggal_kadaluarsa  (→ kadaluwarsa di DB)
     * 10 nilai_akreditasi        | 11 kepemilikan
     * 12 sumber_data_nilai
     * Format tanggal CSV: DD-MM-YYYY
     */
    public function validate(array $row): array
    {
        if (empty($row[0])) {
            throw new \Exception('npsn wajib diisi.');
        }

        return [
            'npsn' => trim($row[0]),
            'nama_sekolah' => $row[1] ?: null,
            'jenis_sekolah' => $row[2] ?: null,
            'kode_kabupaten' => $row[3] ?: null,
            'nama_kabupaten' => $row[4] ?: null,
            'kode_provinsi' => $row[5] ?: null,
            'nama_provinsi' => $row[6] ?: null,
            'akreditasi_sekolah' => $row[7] ?: null,
            'tanggal_mulai_akreditasi' => $this->parseDate($row[8] ?? null),
            'tanggal_kadaluwarsa' => $this->parseDate($row[9] ?? null),
            'nilai_akreditasi' => isset($row[10]) && $row[10] !== '' ? (int) $row[10] : null,
            'kepemilikan' => $row[11] ?: null,
            'sumber_data_nilai' => $row[12] ?: null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function parseDate(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
        } catch (\Exception) {
            return null;
        }
    }
}
