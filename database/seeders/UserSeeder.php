<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin YAKIIN',
            'email' => 'admin@yakiin.sch.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create Bendahara User
        $bendahara = User::create([
            'name' => 'Bendahara YAKIIN',
            'email' => 'bendahara@yakiin.sch.id',
            'password' => Hash::make('bendahara123'),
            'role' => 'bendahara',
            'is_active' => true,
        ]);

        // Create Sample Teacher User
        $guruUser = User::create([
            'name' => 'Guru Contoh',
            'email' => 'guru@yakiin.sch.id',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
            'is_active' => true,
        ]);

        // Create Teacher Data
        Teacher::create([
            'user_id' => $guruUser->id,
            'nip' => '12345678901234567',
            'nama_lengkap' => 'Guru Contoh, S.Pd',
            'alamat' => 'Jl. Contoh No. 123, Jakarta',
            'no_telepon' => '081234567890',
            'jenis_kelamin' => 'laki-laki',
            'tanggal_lahir' => '1985-01-15',
            'tempat_lahir' => 'Jakarta',
            'pendidikan_terakhir' => 'S1 Pendidikan',
            'mata_pelajaran' => 'Matematika',
            'tanggal_masuk' => '2020-07-01',
            'gaji_pokok' => 4500000,
            'tunjangan' => 500000,
            'is_active' => true,
        ]);
    }
}
