<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // UserSeeder::class, // Optional: duplicate user logic handled in ComprehensiveSeeder
            ComprehensiveSeeder::class,
        ]);
    }
}
