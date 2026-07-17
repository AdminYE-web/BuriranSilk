<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        AdminUser::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
                'is_active' => 1,
                'role' => 'super_admin',
            ]
        );

        AdminUser::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('12345678'),
                'is_active' => 1,
                'role' => 'admin',
            ]
        );

        AdminUser::updateOrCreate(
            ['email' => 'admin@buriramsilk.com'],
            [
                'name' => 'Master Brindes Admin',
                'password' => Hash::make('Adm!n#K7p9@2026'),
                'is_active' => 1,
                'role' => 'admin',
            ]
        );
    }
}