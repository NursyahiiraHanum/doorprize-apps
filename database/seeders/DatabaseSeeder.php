<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'superadmin@voyages.com'],
            [
                'name'     => 'Super Admin Voyages',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role'     => 'super_admin',
            ]
        );
    }
}
