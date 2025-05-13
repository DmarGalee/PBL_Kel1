<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('m_user')->insert([
            [
                'level_id' => 1, // Asumsikan Admin adalah level_id 1, periksa sesuai urutan seeder
                'username' => 'admin',
                'nama' => 'Administrator Sistem',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin123'),
                'profile_photo' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'level_id' => 2, // Mahasiswa
                'username' => 'mhs001',
                'nama' => 'Andi Mahasiswa',
                'email' => 'mahasiswa@example.com',
                'password' => Hash::make('password123'),
                'profile_photo' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'level_id' => 3, // Dosen
                'username' => 'dsn001',
                'nama' => 'Budi Dosen',
                'email' => 'dosen@example.com',
                'password' => Hash::make('password123'),
                'profile_photo' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'level_id' => 4, // Tenaga Kependidikan
                'username' => 'tendik001',
                'nama' => 'Citra Tendik',
                'email' => 'tendik@example.com',
                'password' => Hash::make('password123'),
                'profile_photo' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'level_id' => 5, // Sarpras
                'username' => 'sarpras001',
                'nama' => 'Dedi Sarpras',
                'email' => 'sarpras@example.com',
                'password' => Hash::make('password123'),
                'profile_photo' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'level_id' => 6, // Teknisi
                'username' => 'teknisi001',
                'nama' => 'Eka Teknisi',
                'email' => 'teknisi@example.com',
                'password' => Hash::make('password123'),
                'profile_photo' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
