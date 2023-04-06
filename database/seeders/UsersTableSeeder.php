<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::create([
            'name' => 'Admin',
            'lname' => 'Admin',
            'phone' => '00000000000',
            'role_id' => 1,
            'center_id' => 1,
            'email' => 'admin@admin.admin',
            'password' => Hash::make('admin'),
        ]);
    }
}
