<?php

namespace App\Services\Import\Validators;

use Illuminate\Support\Facades\DB;

class DataPortofolioRowValidator
{
    /**
     * Cache ref_portofolio: key = kode, value = kode
     * dan key = nama (lowercase), value = kode.
     * Di-load sekali saat instance pertama kali memvalidasi baris.
     */
    private ?array $lookup = null;

    /**
     * CSV column order: 0 nomor_pendaftaran | 1 nama_portofolio (atau kode_portofolio)
     * Kolom 1 bisa berisi nama_portofolio; jika tidak cocok sebagai kode langsung,
     * akan di-lookup dari tabel ref_portofolio berdasarkan nama.
     * Tabel tidak memiliki timestamps.
     */
    public function validate(array $row): array
    {
        if (empty($row[0])) {
            throw new \Exception('nomor_pendaftaran wajib diisi.');
        }

        $input = trim($row[1] ?? '');

        if ($input === '') {
            throw new \Exception("kode_portofolio / nama_portofolio wajib diisi (baris nomor_pendaftaran={$row[0]}).");
        }

        $this->loadLookup();

        $kode = $this->lookup[$input]
            ?? $this->lookup[mb_strtolower($input)]
            ?? null;

        // Jika tidak ditemukan, tambahkan otomatis ke ref_portofolio
        if ($kode === null) {
            DB::table('ref_portofolio')->insertOrIgnore([
                'kode_portofolio' => $input,
                'nama_portofolio' => $input,
            ]);

            $kode = $input;
            $this->lookup[$input] = $input;
            $this->lookup[mb_strtolower($input)] = $input;
        }

        return [
            'nomor_pendaftaran' => (int) $row[0],
            'kode_portofolio'   => $kode,
        ];
    }

    private function loadLookup(): void
    {
        if ($this->lookup !== null) {
            return;
        }

        $this->lookup = [];

        DB::table('ref_portofolio')
            ->select('kode_portofolio', 'nama_portofolio')
            ->get()
            ->each(function ($ref) {
                // index by kode (exact)
                $this->lookup[$ref->kode_portofolio] = $ref->kode_portofolio;
                // index by nama (exact dan lowercase untuk case-insensitive)
                $this->lookup[$ref->nama_portofolio] = $ref->kode_portofolio;
                $this->lookup[mb_strtolower($ref->nama_portofolio)] = $ref->kode_portofolio;
            });
    }
}
