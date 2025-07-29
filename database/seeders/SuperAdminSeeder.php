<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'shankar@gmail.com')->first();
        if (!$user) {
            $this->command->error('User with email shankar@gmail.com not found.');
            return;
        }
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $allPermissions = Permission::all();
        $superAdminRole->syncPermissions($allPermissions);
        $user->assignRole($superAdminRole);
        $this->command->info('Super Admin role assigned to shankar@gmail.com with all permissions.');
    }
}
