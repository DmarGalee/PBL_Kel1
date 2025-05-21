<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            LevelSeeder::class,
            UserSeeder::class,
            GedungSeeder::class,
            PeriodeSeeder::class,
            LantaiSeeder::class,
            RuangSeeder::class,
            KategoriSeeder::class,
            FasilitasSeeder::class,
        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
