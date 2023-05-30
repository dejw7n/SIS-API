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
            'title' => 'Úvodní příspěvek pro středisko Resslova',
            'content' => 'Jedná se o zkoušku, jestli vše funguje a zdá se, že ano.',
            'priority_id' => 1,
            'center_id' => 1,
            'author_id' => 1,
            'monitors' => false,
        ]);
        Post::create([
            'title' => 'Úvodní příspěvek pro středisko Stříbrníky',
            'content' => 'Jedná se o zkoušku, jestli vše funguje a zdá se, že ano.',
            'priority_id' => 1,
            'center_id' => 2,
            'author_id' => 1,
            'monitors' => false,
        ]);
    }
}
