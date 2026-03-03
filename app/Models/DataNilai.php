<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataNilai extends Model
{
    protected $table = 'data_nilai';

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(DataSiswa::class, 'nomor_pendaftaran', 'nomor_pendaftaran');
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(RefMataPelajaran::class, 'kode_mata_pelajaran', 'kode_mata_pelajaran');
    }

    protected $fillable = [
        'semester',
        'wajib',
        'tingkat',
        'nilai',
        'nilai_validasi_tka',
        'tahun_kur',
        'unit',
        'kkm',
        'asal_sekolah',
    ];

    protected $casts = [
        'wajib' => 'boolean',
    ];
}
