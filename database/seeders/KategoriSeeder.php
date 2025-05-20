<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\KategoriModel;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $kategoris = [
            ['kategori_kode' => 'HW', 'kategori_nama' => 'Hardware'],
            ['kategori_kode' => 'SW', 'kategori_nama' => 'Software'],
            ['kategori_kode' => 'NET', 'kategori_nama' => 'Jaringan'],
            ['kategori_kode' => 'FUR', 'kategori_nama' => 'Furnitur'],
        ];

        foreach ($kategoris as $kategori) {
            KategoriModel::create($kategori);
        }
    }
}