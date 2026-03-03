<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataProdiSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::table('data_prodi')->insertOrIgnore([
            ['kode_prodi' => '11131001', 'nama_prodi' => 'Teknik Sipil'],
            ['kode_prodi' => '11131002', 'nama_prodi' => 'Teknik Mesin'],
            ['kode_prodi' => '11131003', 'nama_prodi' => 'Teknik Industri'],
            ['kode_prodi' => '11131004', 'nama_prodi' => 'Agribisnis'],
            ['kode_prodi' => '11131005', 'nama_prodi' => 'Agroteknologi'],
            ['kode_prodi' => '11131006', 'nama_prodi' => 'Perikanan'],
            ['kode_prodi' => '11131007', 'nama_prodi' => 'Kesehatan Masyarakat'],
            ['kode_prodi' => '11131008', 'nama_prodi' => 'Akuakultur'],
            ['kode_prodi' => '11131009', 'nama_prodi' => 'Sumber Daya Akuatik'],
            ['kode_prodi' => '11131010', 'nama_prodi' => 'Teknologi Hasil Pertanian'],
            ['kode_prodi' => '11131011', 'nama_prodi' => 'Ilmu Kelautan'],
            ['kode_prodi' => '11131012', 'nama_prodi' => 'Teknologi Informasi'],
            ['kode_prodi' => '11131013', 'nama_prodi' => 'GIZI'],
            ['kode_prodi' => '11131014', 'nama_prodi' => 'Ekonomi Pembangunan'],
            ['kode_prodi' => '11131015', 'nama_prodi' => 'Ilmu Administrasi Negara'],
            ['kode_prodi' => '11131016', 'nama_prodi' => 'Sosiologi'],
            ['kode_prodi' => '11131017', 'nama_prodi' => 'Ilmu Komunikasi'],
            ['kode_prodi' => '11131018', 'nama_prodi' => 'Manajemen'],
            ['kode_prodi' => '11131019', 'nama_prodi' => 'Akuntansi'],
            ['kode_prodi' => '11131020', 'nama_prodi' => 'Ilmu Hukum'],
            ['kode_prodi' => '11131021', 'nama_prodi' => 'Peternakan'],
            ['kode_prodi' => '11131022', 'nama_prodi' => 'Bisnis Digital'],
            ['kode_prodi' => '11131023', 'nama_prodi' => 'Keselamatan dan Kesehatan Kerja'],
            ['kode_prodi' => '11131024', 'nama_prodi' => 'Bahasa dan Kebudayaan Inggris'],
            ['kode_prodi' => '11131025', 'nama_prodi' => 'Teknik Infrastruktur dan Lingkungan'],
            ['kode_prodi' => '11131026', 'nama_prodi' => 'Sain dan Keolahragaan'],
            ['kode_prodi' => '11131027', 'nama_prodi' => 'Biosains Hewan'],
        ]);
    }
}
