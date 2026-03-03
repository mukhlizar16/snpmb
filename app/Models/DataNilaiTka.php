<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataNilaiTka extends Model
{
    protected $table = 'data_nilai_tka';

    public $timestamps = false;

    protected $fillable = [
        'kode_mata_pelajaran',
        'nilai_tka',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(DataSiswa::class, 'nomor_pendaftaran', 'nomor_pendaftaran');
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(RefMataPelajaran::class, 'kode_mata_pelajaran', 'kode_mata_pelajaran');
    }
}
