<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataSiswaExport implements WithMultipleSheets
{
    public function __construct(
        private readonly int $noUrut = 0,
        private readonly string $kodeProdi = '',
    ) {}

    public function sheets(): array
    {
        // Tentukan urutan pilihan yang akan di-generate
        $urutanList = $this->noUrut > 0 ? [$this->noUrut] : [1, 2];

        $sheets = [];

        foreach ($urutanList as $urutan) {
            $rows = DB::table('data_pilihan as dp')
                ->join('data_prodi as p', 'p.kode_prodi', '=', 'dp.kode_prodi')
                ->where('dp.no_urut_pilihan', $urutan)
                ->select('dp.kode_prodi', 'p.nama_prodi')
                ->distinct()
                ->orderBy('dp.kode_prodi')
                ->when($this->kodeProdi !== '', fn ($q) => $q->where('dp.kode_prodi', $this->kodeProdi))
                ->get();

            foreach ($rows as $prodi) {
                $sheets[] = new DataSiswaProdiSheet($prodi->kode_prodi, $prodi->nama_prodi, $urutan);
            }
        }

        return $sheets;
    }
}
