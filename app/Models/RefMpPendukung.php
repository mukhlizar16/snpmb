<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefMpPendukung extends Model
{
    protected $table = 'ref_mp_pendukung';

    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = ['kode_prodi', 'id_jurusan', 'kode_mata_pelajaran'];

    protected $fillable = [
        'kode_prodi',
        'id_jurusan',
        'kode_mata_pelajaran',
    ];

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(DataProdi::class, 'kode_prodi', 'kode_prodi');
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(RefJurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(RefMataPelajaran::class, 'kode_mata_pelajaran', 'kode_mata_pelajaran');
    }
}
