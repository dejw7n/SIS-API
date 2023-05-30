<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Issue;
use Illuminate\Support\Facades\Hash;

class IssuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Issue::create([
            'title' => 'UKÁZKA | Vadný projektor v učebně 420',
            'description' => 'Když se snažím projektor připojit k počítači, tak se nic neděje. Zkoušel jsem i jiný kabel, ale nepomohlo to.',
            'priority_id' => 1,
            'status_id' => 1,
            'center_id' => 2,
            'author_id' => 1,
        ]);
        Issue::create([
            'title' => 'UKÁZKA | Nefunkční učitelská internetová zásuvka v učebně 420',
            'description' => 'Je rozbitá a nejde se do ní připojit.',
            'priority_id' => 1,
            'status_id' => 2,
            'center_id' => 2,
            'author_id' => 1,
        ]);
        Issue::create([
            'title' => 'UKÁZKA | Vadný školní počítač č. 12 v učebně 420',
            'description' => 'Počítač se zapne, ale nejde se na něm přihlásit. Zobrazuje se chybová hláška "The user profile service failed the logon".',
            'priority_id' => 1,
            'status_id' => 3,
            'center_id' => 2,
            'author_id' => 1,
        ]);
    }
}
