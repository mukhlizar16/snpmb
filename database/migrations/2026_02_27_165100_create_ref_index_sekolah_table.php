<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_index_sekolah', function (Blueprint $table) {
            $table->string('npsn', 20)->primary();
            $table->decimal('index_sekolah', 8, 4)->nullable();

            $table->foreign('npsn')->references('npsn')->on('data_sekolah')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_index_sekolah');
    }
};
