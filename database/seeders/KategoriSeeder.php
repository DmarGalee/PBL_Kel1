<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('m_kategori')->insert([
            [
                'kategori_kode' => 'HW',
                'kategori_nama' => 'Hardware',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kategori_kode' => 'SW',
                'kategori_nama' => 'Software',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kategori_kode' => 'NET',
                'kategori_nama' => 'Jaringan',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kategori_kode' => 'FUR',
                'kategori_nama' => 'Furnitur',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
