<?php

namespace App\Services\Import\Validators;

use Illuminate\Support\Facades\DB;

class RefIndexSekolahRowValidator
{
    /**
     * Column order: 0 npsn | 1 index_sekolah
     * Tabel tidak memiliki timestamps.
     * NPSN yang tidak ada di data_sekolah akan di-skip (return null).
     */
    private static ?array $validNpsn = null;

    private function getValidNpsn(): array
    {
        if (self::$validNpsn === null) {
            self::$validNpsn = DB::table('data_sekolah')->pluck('npsn')->flip()->all();
        }

        return self::$validNpsn;
    }

    public function validate(array $row): ?array
    {
        $npsn = trim((string) ($row[0] ?? ''));

        if ($npsn === '' || $npsn === '0') {
            return null; // baris kosong — skip
        }

        if (! isset($this->getValidNpsn()[$npsn])) {
            return null; // skip — NPSN tidak ada di data_sekolah
        }

        return [
            'npsn'          => $npsn,
            'index_sekolah' => isset($row[1]) && $row[1] !== '' ? (float) str_replace(',', '.', $row[1]) : null,
        ];
    }
}
