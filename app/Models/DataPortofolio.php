<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPortofolio extends Model
{
    protected $table = 'data_portofolio';

    protected $fillable = [
        'nomor_pendaftaran',
        'kode_portofolio',
    ];
}
