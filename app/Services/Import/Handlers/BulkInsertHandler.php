<?php

namespace App\Services\Import\Handlers;

use Illuminate\Support\Facades\DB;

class BulkInsertHandler
{
    const CHUNK_SIZE = 500;

    /**
     * Tabel referensi: gunakan upsert (update bila PK sudah ada).
     * Key = nama tabel, value = kolom unik.
     */
    protected array $upsertTables = [
        'ref_jurusan' => ['id_jurusan'],
        'ref_mata_pelajaran' => ['kode_mata_pelajaran'],
        'ref_mp_pendukung' => ['kode_prodi', 'id_jurusan', 'kode_mata_pelajaran'],
        'ref_portofolio' => ['kode_portofolio'],
        'data_sekolah' => ['npsn'],
    ];

    /**
     * Tabel dengan unique constraint: gunakan insertOrIgnore agar
     * baris duplikat dilewati tanpa menggagalkan seluruh batch.
     */
    protected array $ignoreTables = [
        'data_siswa',
        'data_nilai',
    ];

    /**
     * Tabel yang di-truncate terlebih dahulu sebelum insert.
     * Data lama akan dihapus sepenuhnya, lalu diganti data baru.
     */
    protected array $truncateTables = [
        'ref_index_sekolah',
    ];

    protected array $tables = [
        'ref_jurusan',
        'ref_mata_pelajaran',
        'ref_mp_pendukung',
        'ref_index_sekolah',
        'ref_portofolio',
        'data_sekolah',
        'data_siswa',
        'data_pilihan',
        'data_prestasi',
        'data_nilai',
        'data_nilai_tka',
        'data_portofolio',
        'data_status_tambahan',
    ];

    public function needsTruncate(string $type): bool
    {
        return in_array($type, $this->truncateTables);
    }

    public function insert(string $type, array $rows): int
    {
        if (! in_array($type, $this->tables)) {
            throw new \Exception("Tipe insert tidak dikenali: {$type}");
        }

        if (empty($rows)) {
            return 0;
        }

        foreach (array_chunk($rows, self::CHUNK_SIZE) as $chunk) {
            if (isset($this->upsertTables[$type])) {
                $uniqueBy = $this->upsertTables[$type];
                $updateCols = array_keys($chunk[0]);
                DB::table($type)->upsert($chunk, $uniqueBy, $updateCols);
            } elseif (in_array($type, $this->ignoreTables)) {
                DB::table($type)->insertOrIgnore($chunk);
            } else {
                DB::table($type)->insert($chunk);
            }
        }

        return count($rows);
    }
}
