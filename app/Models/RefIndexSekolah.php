<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefIndexSekolah extends Model
{
    protected $table = 'ref_index_sekolah';

    protected $primaryKey = 'npsn';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'npsn',
        'index_sekolah',
    ];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(DataSekolah::class, 'npsn', 'npsn');
    }
}
