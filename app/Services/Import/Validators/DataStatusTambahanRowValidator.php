<?php

namespace App\Services\Import\Validators;

class DataStatusTambahanRowValidator
{
    /**
     * CSV column order (positional):
     * 0 nomor_pendaftaran | 1 tingkat | 2 semester
     * 3 deskripsi (→ kode_status_tambahan di DB)
     * 4 id_jurusan        | 5 npsn_sekolah | 6 nisn_siswa
     * Tabel tidak memiliki timestamps.
     */
    public function validate(array $row): array
    {
        if (empty($row[0])) {
            throw new \Exception('nomor_pendaftaran wajib diisi.');
        }

        return [
            'nomor_pendaftaran' => (int) $row[0],
            'tingkat' => isset($row[1]) && $row[1] !== '' ? (int) $row[1] : null,
            'semester' => isset($row[2]) && $row[2] !== '' ? (int) $row[2] : null,
            'kode_status_tambahan' => $row[3] ?: null,
            'id_jurusan' => $row[4] ?: null,
            'npsn_sekolah' => $row[5] ?: null,
            'nisn_siswa' => $row[6] ?: null,
        ];
    }
}
