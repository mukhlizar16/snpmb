<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DataSekolah extends Model
{
    protected $table = 'data_sekolah';

    use \Illuminate\Database\Eloquent\Concerns\HasUuids;

    protected $primaryKey = 'npsn';

    public $incrementing = false;

    protected $keyType = 'string';

    public function siswas(): HasMany
    {
        return $this->hasMany(DataSiswa::class, 'npsn_sekolah', 'npsn');
    }

    public function indexSekolah(): HasOne
    {
        return $this->hasOne(RefIndexSekolah::class, 'npsn', 'npsn');
    }

    protected $fillable = [
        'nama_sekolah',
        'jenis_sekolah',
        'kode_kabupaten',
        'nama_kabupaten',
        'kode_provinsi',
        'nama_provinsi',
        'akreditasi_sekolah',
        'tanggal_mulai_akreditasi',
        'tanggal_kadaluwarsa',
        'nilai_akreditasi',
        'kepemilikan',
        'sumber_data_nilai',
    ];
}
