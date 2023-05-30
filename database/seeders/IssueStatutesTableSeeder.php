<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\IssueStatus;
use Illuminate\Support\Facades\Hash;

class IssueStatutesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IssueStatus::create([
            'name' => 'pending',
            'title' => 'Čeká na vyřízení',
        ]);
        IssueStatus::create([
            'name' => 'handling',
            'title' => 'Vyřizuje se',
        ]);
        IssueStatus::create([
            'name' => 'resolved',
            'title' => 'Vyřízeno',
        ]);
    }
}
