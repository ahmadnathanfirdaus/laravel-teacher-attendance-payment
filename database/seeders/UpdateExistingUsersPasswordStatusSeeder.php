<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateExistingUsersPasswordStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update all existing users to mark them as having changed their password
        // This prevents existing users from being forced to change their password
        User::whereNull('password_changed')
            ->orWhere('password_changed', false)
            ->update([
                'password_changed' => true,
                'first_login_at' => now(),
            ]);

        $this->command->info('Updated existing users password status.');
    }
}
