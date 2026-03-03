<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPrestasi extends Model
{
    protected $table = 'data_prestasi';

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(DataSiswa::class, 'nomor_pendaftaran', 'nomor_pendaftaran');
    }

    protected $fillable = [
        'no_prestasi',
        'jenis_prestasi',
        'jenjang_prestasi',
        'individu_berkelompok',
        'lembaga_pemberi_prestasi',
        'tahun',
        'nilai_prestasi',
        'deskripsi',
        'file_sertifikat',
    ];
}
