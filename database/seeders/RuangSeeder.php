<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('m_ruang')->insert([
            [
                'ruang_nama' => 'RT 1',
                'lantai_id' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'ruang_nama' => 'LKJ 1',
                'lantai_id' => 7,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'ruang_nama' => 'RT 2',
                'lantai_id' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'ruang_nama' => 'LAI 1',
                'lantai_id' => 7,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'ruang_nama' => 'LIG 1',
                'lantai_id' => 7,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}