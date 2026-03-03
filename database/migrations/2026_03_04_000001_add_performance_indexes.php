<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Covering index untuk subquery pilihan:
        // WHERE nomor_pendaftaran = ? AND no_urut_pilihan = ? LIMIT 1
        Schema::table('data_pilihan', function (Blueprint $table) {
            $table->index(
                ['nomor_pendaftaran', 'no_urut_pilihan', 'kode_prodi'],
                'data_pilihan_np_urut_prodi_idx'
            );
        });

        // Composite index untuk AVG subquery:
        // WHERE nomor_pendaftaran = ? AND nilai_tka IS NOT NULL
        Schema::table('data_nilai_tka', function (Blueprint $table) {
            $table->index(
                ['nomor_pendaftaran', 'nilai_tka'],
                'data_nilai_tka_np_nilai_idx'
            );
        });

        // Composite index untuk MAX GROUP BY subquery:
        // GROUP BY nomor_pendaftaran, MAX(nilai_prestasi)
        Schema::table('data_prestasi', function (Blueprint $table) {
            $table->index(
                ['nomor_pendaftaran', 'nilai_prestasi'],
                'data_prestasi_np_nilai_idx'
            );
        });

        // Composite index untuk COUNT subquery:
        // WHERE nomor_pendaftaran = ? AND nilai IS NOT NULL AND kkm IS NOT NULL AND nilai < kkm
        Schema::table('data_nilai', function (Blueprint $table) {
            $table->index(
                ['nomor_pendaftaran', 'nilai', 'kkm'],
                'data_nilai_np_nilai_kkm_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('data_pilihan', function (Blueprint $table) {
            $table->dropIndex('data_pilihan_np_urut_prodi_idx');
        });

        Schema::table('data_nilai_tka', function (Blueprint $table) {
            $table->dropIndex('data_nilai_tka_np_nilai_idx');
        });

        Schema::table('data_prestasi', function (Blueprint $table) {
            $table->dropIndex('data_prestasi_np_nilai_idx');
        });

        Schema::table('data_nilai', function (Blueprint $table) {
            $table->dropIndex('data_nilai_np_nilai_kkm_idx');
        });
    }
};
