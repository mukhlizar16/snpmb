<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataProdi extends Model
{
    protected $table = 'data_prodi';
    use \Illuminate\Database\Eloquent\Concerns\HasUuids;

    protected $primaryKey = 'kode_prodi';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'nama_prodi'
    ];

    public function pilihans(): HasMany
    {
        return $this->hasMany(DataPilihan::class, 'kode_prodi', 'kode_prodi');
    }

    public function mpPendukung(): HasMany
    {
        return $this->hasMany(RefMpPendukung::class, 'kode_prodi', 'kode_prodi');
    }
}
