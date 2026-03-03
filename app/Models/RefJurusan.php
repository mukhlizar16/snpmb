<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefJurusan extends Model
{
    protected $table = 'ref_jurusan';

    protected $primaryKey = 'id_jurusan';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'nama_jurusan',
    ];

    public function siswas(): HasMany
    {
        return $this->hasMany(DataSiswa::class, 'id_jurusan', 'id_jurusan');
    }

    public function mpPendukung(): HasMany
    {
        return $this->hasMany(RefMpPendukung::class, 'id_jurusan', 'id_jurusan');
    }
}
