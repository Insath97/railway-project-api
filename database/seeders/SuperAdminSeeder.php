<?php

namespace Database\Seeders;

use App\Models\Aadmin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    protected static ?string $password;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Step 1: Create Super Admin Role with 'admin' Guard
        $role = Role::firstOrCreate(
            ['name' => 'Super Admin', 'guard_name' => 'admin'] // Set guard_name to 'admin'
        );

        // Step 2: Assign All Permissions to Super Admin Role
        $permissions = Permission::where('guard_name', 'admin')->get(); // Retrieve permissions for the 'admin' guard
        $role->syncPermissions($permissions); // Assign all permissions to the Super Admin role

        // Step 3: Create Super Admin User
        $superAdmin = Aadmin::firstOrCreate(
            ['email' => 'superadmin@example.com'], // Replace with your desired email
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'), // Replace with a secure password
            ]
        );

        // Step 4: Assign Super Admin Role to the User
        $superAdmin->assignRole($role);

        // Log the result to the console
        $this->command->info('Super Admin role and user created with all permissions successfully.');
    }
}
