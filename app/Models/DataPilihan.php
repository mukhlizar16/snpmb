<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataPilihan extends Model
{
    protected $table = 'data_pilihan';

    public $timestamps = false;

    protected $fillable = [
        'no_urut_pilihan',
        'kode_prodi',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(DataSiswa::class, 'nomor_pendaftaran', 'nomor_pendaftaran');
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(DataProdi::class, 'kode_prodi', 'kode_prodi');
    }

    public function insert(string $type, array $rows): void
    {
        match ($type) {
            'siswa' => DataSiswa::insert($rows),
            'pilihan' => DataPilihan::insert($rows),
            default => throw new \Exception('Bulk insert type tidak dikenali.')
        };
    }
}
