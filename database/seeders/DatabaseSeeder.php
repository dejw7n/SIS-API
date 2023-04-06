<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(\Database\Seeders\CentersTableSeeder::class);
        $this->call(\Database\Seeders\RolesTableSeeder::class);
        $this->call(\Database\Seeders\UsersTableSeeder::class);
        $this->call(\Database\Seeders\PrioritiesTableSeeder::class);
        $this->call(\Database\Seeders\PostsTableSeeder::class);
    }
}
