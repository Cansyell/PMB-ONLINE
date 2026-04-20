<?php
// database/seeders/AgamaSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agama;

class AgamaSeeder extends Seeder
{
    public function run(): void
    {
        $agamas = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
        foreach ($agamas as $nama) {
            Agama::firstOrCreate(['nama' => $nama]);
        }
    }
}