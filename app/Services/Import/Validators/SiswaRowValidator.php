<?php

namespace App\Services\Import\Validators;

use Carbon\Carbon;

class SiswaRowValidator
{
    /**
     * CSV column order (positional):
     * 0  nomor_pendaftaran | 1  nama_siswa          | 2  file_foto
     * 3  id_jurusan        | 4  npsn_sekolah         | 5  kode_jenis_kelamin
     * 6  tanggal_lahir     | 7  nisn                 | 8  nik
     * 9  status_pindahan   | 10 status_pertukaran    | 11 status_cuti
     * 12 ranking           | 13 jumlah_siswa_jurusan | 14 rata_nilai_rapor
     * 15 rata_adjusted     | 16 rata_tanpa_sem_akhir | 17 rata_tambahan
     * 18 kip-k             | 19 kategori_kip         | 20 tipe_studi
     * 21 jumlah_tanggungan | 22 penghasilan_ayah     | 23 penghasilan_ibu
     * 24 kebutuhan_khusus
     */
    public function validate(array $row): array
    {
        if (empty($row[0])) {
            throw new \Exception('nomor_pendaftaran wajib diisi.');
        }

        $tanggalLahir = null;
        if (! empty($row[6])) {
            try {
                $tanggalLahir = Carbon::parse($row[6])->format('Y-m-d');
            } catch (\Exception) {
                $tanggalLahir = null;
            }
        }

        return [
            'nomor_pendaftaran' => (int) $row[0],
            'nama_siswa' => $row[1] ?: null,
            'file_foto' => $row[2] ?: null,
            'id_jurusan' => $row[3] ?: null,
            'npsn_sekolah' => $row[4] ?: null,
            'kode_jenis_kelamin' => $row[5] ?: null,
            'tanggal_lahir' => $tanggalLahir,
            'nisn' => $row[7] ?: null,
            'nik' => $row[8] ?: null,
            'status_siswa_pindahan' => (bool) ($row[9] ?? false),
            'status_siswa_pertukaran_pelajar' => (bool) ($row[10] ?? false),
            'status_siswa_cuti' => (bool) ($row[11] ?? false),
            'ranking_versi_sekolah' => isset($row[12]) && $row[12] !== '' ? (int) $row[12] : null,
            'jumlah_siswa_jurusan' => isset($row[13]) && $row[13] !== '' ? (int) $row[13] : null,
            'rata_nilai_rapor' => isset($row[14]) && $row[14] !== '' ? (float) $row[14] : null,
            'rata_nilai_rapor_adjusted' => isset($row[15]) && $row[15] !== '' ? (float) $row[15] : null,
            'rata_nilai_rapor_tanpa_sem_akhir' => isset($row[16]) && $row[16] !== '' ? (float) $row[16] : null,
            'rata_nilai_rapor_tambahan' => isset($row[17]) && $row[17] !== '' ? (float) $row[17] : null,
            'kip_k' => $row[18] ?: null,
            'kategori_kip' => isset($row[19]) && $row[19] !== '' ? (int) $row[19] : null,
            'tipe_studi' => $row[20] ?: null,
            'jumlah_tanggungan' => isset($row[21]) && $row[21] !== '' ? (int) $row[21] : null,
            'penghasilan_ayah' => $row[22] ?: null,
            'penghasilan_ibu' => $row[23] ?: null,
            'kebutuhan_khusus' => $row[24] ?: null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
