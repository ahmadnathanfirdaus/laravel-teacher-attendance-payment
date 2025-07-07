<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            [
                'name' => 'Kepala Sekolah',
                'description' => 'Pimpinan tertinggi sekolah',
                'base_allowance' => 2000000,
                'is_active' => true,
            ],
            [
                'name' => 'Wakil Kepala Sekolah',
                'description' => 'Wakil pimpinan sekolah',
                'base_allowance' => 1500000,
                'is_active' => true,
            ],
            [
                'name' => 'Guru Kelas',
                'description' => 'Guru yang mengajar di kelas tertentu',
                'base_allowance' => 500000,
                'is_active' => true,
            ],
            [
                'name' => 'Guru Mata Pelajaran',
                'description' => 'Guru yang mengajar mata pelajaran khusus',
                'base_allowance' => 400000,
                'is_active' => true,
            ],
            [
                'name' => 'Wali Kelas',
                'description' => 'Guru yang menjadi wali kelas',
                'base_allowance' => 300000,
                'is_active' => true,
            ],
            [
                'name' => 'Staff TU',
                'description' => 'Staff tata usaha',
                'base_allowance' => 200000,
                'is_active' => true,
            ],
            [
                'name' => 'Guru Honorer',
                'description' => 'Guru tidak tetap/honorer',
                'base_allowance' => 100000,
                'is_active' => true,
            ],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
    }
}
