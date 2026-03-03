<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefMataPelajaran extends Model
{
    protected $table = 'ref_mata_pelajaran';

    use \Illuminate\Database\Eloquent\Concerns\HasUuids;

    protected $primaryKey = 'kode_mata_pelajaran';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'nama_mata_pelajaran',
    ];

    public function nilais(): HasMany
    {
        return $this->hasMany(DataNilai::class, 'kode_mata_pelajaran', 'kode_mata_pelajaran');
    }

    public function nilaiTkas(): HasMany
    {
        return $this->hasMany(DataNilaiTka::class, 'kode_mata_pelajaran', 'kode_mata_pelajaran');
    }

    public function mpPendukung(): HasMany
    {
        return $this->hasMany(RefMpPendukung::class, 'kode_mata_pelajaran', 'kode_mata_pelajaran');
    }
}
