<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = [
            [
                'name' => 'Shift Pagi',
                'start_time' => '07:00',
                'end_time' => '12:00',
                'description' => 'Shift pagi untuk guru kelas pagi',
                'is_active' => true,
            ],
            [
                'name' => 'Shift Siang',
                'start_time' => '12:30',
                'end_time' => '17:30',
                'description' => 'Shift siang untuk guru kelas siang',
                'is_active' => true,
            ],
            [
                'name' => 'Shift Penuh',
                'start_time' => '07:00',
                'end_time' => '15:00',
                'description' => 'Shift penuh untuk guru dan staff administrasi',
                'is_active' => true,
            ],
            [
                'name' => 'Shift Sore',
                'start_time' => '15:00',
                'end_time' => '18:00',
                'description' => 'Shift sore untuk ekstrakurikuler dan bimbingan',
                'is_active' => true,
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}
