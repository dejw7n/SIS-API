<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Priority;

class PrioritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Priority::create([
            'name' => 'Nedůležité',
        ]);
        Priority::create([
            'name' => 'Upozornění',
        ]);
        Priority::create([
            'name' => 'Důležité',
        ]);
    }
}
