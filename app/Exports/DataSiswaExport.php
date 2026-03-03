<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DataSiswaExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return DB::table('data_pilihan as dp')
            ->join('data_prodi as p', 'p.kode_prodi', '=', 'dp.kode_prodi')
            ->where('dp.no_urut_pilihan', 1)
            ->select('dp.kode_prodi', 'p.nama_prodi')
            ->distinct()
            ->orderBy('dp.kode_prodi')
            ->get()
            ->map(fn ($prodi) => new DataSiswaProdiSheet($prodi->kode_prodi, $prodi->nama_prodi))
            ->all();
    }
}
