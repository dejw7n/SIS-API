<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::create([
            'title' => 'UKÁZKA | Projekt gamifikace školy',
            'short_description' => 'Přetvoření školního známkování do podoby hry',
            'description' => 'Podrobné vysvětlení projektu...',
            'author_id' => 1,
        ]);
        Project::create([
            'title' => 'UKÁZKA | Aplikace pro správu školních projektů zadáných žákům.',
            'short_description' => 'Evidence projektů, které žáci mají zadány + známky',
            'description' => 'Podrobné vysvětlení projektu...',
            'author_id' => 1,
        ]);
    }
}
