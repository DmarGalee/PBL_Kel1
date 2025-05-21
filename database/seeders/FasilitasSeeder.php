<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FasilitasSeeder extends Seeder
{
    public function run(): void
    {
        $fasilitas = [
            [
                'ruang_id' => 1,
                'kategori_id' => 1,  // Hardware
                'fasilitas_kode' => 'F-001',
                'deskripsi' => 'Proyektor Epson EB-X05',
                'status' => 'baik',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'ruang_id' => 1,
                'kategori_id' => 1,  // Hardware
                'fasilitas_kode' => 'F-002',
                'deskripsi' => 'AC Panasonic 1PK',
                'status' => 'baik',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'ruang_id' => 2,
                'kategori_id' => 4,  // Furnitur (disesuaikan dengan KategoriSeeder)
                'fasilitas_kode' => 'F-003',
                'deskripsi' => 'Kursi Plastik Merah',
                'status' => 'rusak_ringan',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'ruang_id' => 3,
                'kategori_id' => 3,  // Jaringan
                'fasilitas_kode' => 'F-004',
                'deskripsi' => 'Switch Cisco 24 Port',
                'status' => 'baik',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'ruang_id' => 4,
                'kategori_id' => 2,  // Software
                'fasilitas_kode' => 'F-005',
                'deskripsi' => 'Lisensi Windows 10 Pro',
                'status' => 'baik',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('m_fasilitas')->insert($fasilitas);
    }
}