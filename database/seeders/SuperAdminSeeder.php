<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = User::create([
            'name' => 'Shankar',
            'email' => 'shankar@gmail.com',
            'mobile' => '9999999999',
            'role_id' => 1,
            'password' => Hash::make('password'),
        ]);
        $superadmin->assignRole('SUPERADMIN');
    }
}
