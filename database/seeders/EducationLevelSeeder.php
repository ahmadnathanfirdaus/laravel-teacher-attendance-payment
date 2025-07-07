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
            [
                'name' => 'MA',
                'full_name' => 'Madrasah Aliyah',
                'description' => 'Jenjang pendidikan menengah atas setara SMA',
                'level_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'SD',
                'full_name' => 'Sekolah Dasar',
                'description' => 'Jenjang pendidikan dasar',
                'level_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'SMP',
                'full_name' => 'Sekolah Menengah Pertama',
                'description' => 'Jenjang pendidikan menengah pertama',
                'level_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'SMA',
                'full_name' => 'Sekolah Menengah Atas',
                'description' => 'Jenjang pendidikan menengah atas',
                'level_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'SMK',
                'full_name' => 'Sekolah Menengah Kejuruan',
                'description' => 'Jenjang pendidikan menengah kejuruan',
                'level_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($educationLevels as $level) {
            EducationLevel::create($level);
        }
    }
}
