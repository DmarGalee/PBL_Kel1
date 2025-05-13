<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('m_level')->insert([
            [
                'level_kode' => 'ADM',
                'level_nama' => 'Admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'level_kode' => 'MHS',
                'level_nama' => 'Mahasiswa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'level_kode' => 'DSN',
                'level_nama' => 'Dosen',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'level_kode' => 'TENDIK',
                'level_nama' => 'Tenaga Kependidikan',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'level_kode' => 'SARPRAS',
                'level_nama' => 'Sarana dan Prasarana',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'level_kode' => 'TEKNISI',
                'level_nama' => 'Teknisi',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
