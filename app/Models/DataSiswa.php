<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataSiswa extends Model
{
    protected $table = 'data_siswa';

    protected $fillable = [
        'nomor_pendaftaran',
        'nama_siswa',
        'file_foto',
        'id_jurusan',
        'npsn_sekolah',
        'kode_jenis_kelamin',
        'tanggal_lahir',
        'nisn',
        'nik',
        'status_siswa_pindahan',
        'status_siswa_pertukaran_pelajar',
        'status_siswa_cuti',
        'ranking_versi_sekolah',
        'jumlah_siswa_jurusan',
        'rata_nilai_rapor',
        'rata_nilai_rapor_adjusted',
        'rata_nilai_rapor_tanpa_sem_akhir',
        'rata_nilai_rapor_tambahan',
        'kip_k',
        'kategori_kip',
        'tipe_studi',
        'jumlah_tanggungan',
        'penghasilan_ayah',
        'penghasilan_ibu',
        'kebutuhan_khusus',
    ];

    protected $casts = [
        'status_siswa_pindahan' => 'boolean',
        'status_siswa_pertukaran_pelajar' => 'boolean',
        'status_siswa_cuti' => 'boolean',
    ];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(DataSekolah::class, 'npsn_sekolah', 'npsn');
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(RefJurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function pilihans(): HasMany
    {
        return $this->hasMany(DataPilihan::class, 'nomor_pendaftaran', 'nomor_pendaftaran');
    }

    public function prestasis(): HasMany
    {
        return $this->hasMany(DataPrestasi::class, 'nomor_pendaftaran', 'nomor_pendaftaran');
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(DataNilai::class, 'nomor_pendaftaran', 'nomor_pendaftaran');
    }

    public function nilaiTkas(): HasMany
    {
        return $this->hasMany(DataNilaiTka::class, 'nomor_pendaftaran', 'nomor_pendaftaran');
    }
}
