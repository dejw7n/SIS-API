<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::create([
            'title' => 'První příspěvek',
            'content' => 'Zkušební příspěvek',
            'priority_id' => 1,
            'center_id' => 1,
            'author_id' => 1,
        ]);
    }
}
