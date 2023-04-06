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
            'priority' => '3',
            'name' => 'Důležité',
        ]);
        Priority::create([
            'priority' => '2',
            'name' => 'Upozornění',
        ]);
        Priority::create([
            'priority' => '1',
            'name' => 'Nedůležité',
        ]);
    }
}
