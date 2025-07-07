<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $educationLevels = [
            [
                'name' => 'MI',
                'full_name' => 'Madrasah Ibtidaiyah',
                'description' => 'Jenjang pendidikan dasar setara SD',
                'level_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'MTs',
                'full_name' => 'Madrasah Tsanawiyah',
                'description' => 'Jenjang pendidikan menengah pertama setara SMP',
                'level_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($educationLevels as $level) {
            EducationLevel::create($level);
        }
    }
}
