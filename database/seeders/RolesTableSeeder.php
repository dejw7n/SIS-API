<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'role' => 'admin',
            'name' => 'Admin',
        ]);
        Role::create([
            'role' => 'management',
            'name' => 'Vedení',
        ]);
        Role::create([
            'role' => 'teacher',
            'name' => 'Učitel',
        ]);
    }
}
