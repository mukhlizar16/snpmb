<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataStatusTambahan extends Model
{
    protected $table = 'data_status_tambahan';

    protected $fillable = [
        'tingkat',
        'semester',
        'kode_status_tambahan',
        'id_jurusan',
        'npsn_sekolah',
        'nisn_siswa',
    ];
}
