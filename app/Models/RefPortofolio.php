<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefPortofolio extends Model
{
    protected $table = 'ref_portofolio';

    use \Illuminate\Database\Eloquent\Concerns\HasUuids;

    protected $primaryKey = 'kode_portofolio';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'jenis_portofolio',
        'nama_portofolio',
    ];
}
