<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IssuePrioritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IssuePriority::create([
            'title' => 'První příspěvek + monitory',
            'content' => 'Zkušební příspěvek',
            'priority_id' => 1,
            'center_id' => 1,
            'author_id' => 1,
            'monitors' => true,
        ]);
    }
}
