<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Center;

class CentersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Center::create([
            'name' => 'Resslova',
        ]);
        Center::create([
            'name' => 'Stříbrníky',
        ]);
        Center::create([
            'name' => 'Resslova + Stříbrníky',
        ]);
    }
}
