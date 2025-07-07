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
                'name' => 'Tunjangan Kinerja',
                'description' => 'Tunjangan berdasarkan kinerja',
                'default_amount' => 1000000,
                'is_active' => true,
            ],
        ];

        foreach ($allowanceTypes as $allowanceType) {
            AllowanceType::create($allowanceType);
        }
    }
}
