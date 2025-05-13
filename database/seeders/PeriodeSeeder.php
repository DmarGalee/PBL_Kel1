<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('m_periode')->insert([
            [
                'periode_id' => 2023,
                'periode_name' => 'Tahun Akademik 2022/2023',
                'is_active' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'periode_id' => 2024,
                'periode_name' => 'Tahun Akademik 2023/2024',
                'is_active' => 1, // Aktif
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'periode_id' => 2025,
                'periode_name' => 'Tahun Akademik 2024/2025',
                'is_active' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
