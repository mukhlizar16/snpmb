<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomor_pendaftaran')->unique();

            $table->string('nama_siswa');
            $table->string('file_foto')->nullable();

            $table->string('id_jurusan', 20)->nullable();
            $table->foreign('id_jurusan')->references('id_jurusan')->on('ref_jurusan')->nullOnDelete();
            $table->string('npsn_sekolah', 20)->nullable();
            $table->foreign('npsn_sekolah')->references('npsn')->on('data_sekolah')->nullOnDelete();
            $table->string('kode_jenis_kelamin', 10)->nullable();

            $table->date('tanggal_lahir')->nullable();

            $table->string('nisn', 20)->nullable()->unique();
            $table->string('nik', 20)->nullable();

            $table->boolean('status_siswa_pindahan')->default(false);
            $table->boolean('status_siswa_pertukaran_pelajar')->default(false);
            $table->boolean('status_siswa_cuti')->default(false);

            $table->integer('ranking_versi_sekolah')->nullable();
            $table->integer('jumlah_siswa_jurusan')->nullable();

            $table->decimal('rata_nilai_rapor', 5, 2)->nullable();
            $table->decimal('rata_nilai_rapor_adjusted', 5, 2)->nullable();
            $table->decimal('rata_nilai_rapor_tanpa_sem_akhir', 5, 2)->nullable();
            $table->decimal('rata_nilai_rapor_tambahan', 5, 2)->nullable();

            $table->string('kip_k')->nullable();
            $table->integer('kategori_kip')->nullable();

            $table->string('tipe_studi')->nullable();
            $table->integer('jumlah_tanggungan')->nullable();

            $table->string('penghasilan_ayah')->nullable();
            $table->string('penghasilan_ibu')->nullable();

            $table->string('kebutuhan_khusus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_siswa');
    }
};
