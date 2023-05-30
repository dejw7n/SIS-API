<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\IssuePriority;

class IssuePrioritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IssuePriority::create([
            'name' => 'unimportant',
            'title' => 'Nespěchá to',
            'value' => 1,
        ]);
        IssuePriority::create([
            'name' => 'important',
            'title' => 'Co nejdříve',
            'value' => 2,
        ]);
    }
}
