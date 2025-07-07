<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AllowanceType;

class AllowanceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allowanceTypes = [
            [
                'name' => 'Tunjangan Transportasi',
                'description' => 'Tunjangan untuk biaya transportasi',
                'default_amount' => 300000,
                'is_active' => true,
            ],
            [
                'name' => 'Tunjangan Makan',
                'description' => 'Tunjangan untuk biaya makan',
                'default_amount' => 500000,
                'is_active' => true,
            ],
            [
                'name' => 'Tunjangan Komunikasi',
                'description' => 'Tunjangan untuk biaya komunikasi',
                'default_amount' => 150000,
                'is_active' => true,
            ],
            [
                'name' => 'Tunjangan Kinerja',
                'description' => 'Tunjangan berdasarkan kinerja',
                'default_amount' => 1000000,
                'is_active' => true,
            ],
            [
                'name' => 'Tunjangan Kehadiran',
                'description' => 'Tunjangan berdasarkan kehadiran',
                'default_amount' => 200000,
                'is_active' => true,
            ],
            [
                'name' => 'Tunjangan Lembur',
                'description' => 'Tunjangan untuk kerja lembur',
                'default_amount' => 100000,
                'is_active' => true,
            ],
            [
                'name' => 'Tunjangan Hari Raya',
                'description' => 'Tunjangan khusus hari raya',
                'default_amount' => 1000000,
                'is_active' => true,
            ],
            [
                'name' => 'Tunjangan Pendidikan',
                'description' => 'Tunjangan untuk pengembangan pendidikan',
                'default_amount' => 250000,
                'is_active' => true,
            ],
        ];

        foreach ($allowanceTypes as $allowanceType) {
            AllowanceType::create($allowanceType);
        }
    }
}
