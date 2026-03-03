<?php

namespace App\Services\Import\Validators;

use Illuminate\Support\Facades\DB;

class RefMpPendukungRowValidator
{
    private array $validProdi        = [];
    private array $validJurusan      = [];
    private array $validMataPelajaran = [];

    public function __construct()
    {
        $this->validProdi         = DB::table('data_prodi')->pluck('kode_prodi', 'kode_prodi')->all();
        $this->validJurusan       = DB::table('ref_jurusan')->pluck('id_jurusan', 'id_jurusan')->all();
        $this->validMataPelajaran = DB::table('ref_mata_pelajaran')->pluck('kode_mata_pelajaran', 'kode_mata_pelajaran')->all();
    }

    /**
     * Column order: 0 kode_prodi | 1 id_jurusan | 2 kode_mata_pelajaran
     * Tabel tidak memiliki timestamps.
     */
    public function validate(array $row): ?array
    {
        $kodeProdi        = trim((string) ($row[0] ?? ''));
        $idJurusan        = trim((string) ($row[1] ?? ''));
        $kodeMataPelajaran = trim((string) ($row[2] ?? ''));

        if ($kodeProdi === '' || $idJurusan === '' || $kodeMataPelajaran === '') {
            return null; // baris kosong — skip
        }

        // Skip jika FK tidak ditemukan di tabel referensi
        if (! isset($this->validProdi[$kodeProdi])) {
            return null;
        }
        if (! isset($this->validJurusan[$idJurusan])) {
            return null;
        }
        if (! isset($this->validMataPelajaran[$kodeMataPelajaran])) {
            return null;
        }

        return [
            'kode_prodi'          => $kodeProdi,
            'id_jurusan'          => $idJurusan,
            'kode_mata_pelajaran' => $kodeMataPelajaran,
        ];
    }
}
