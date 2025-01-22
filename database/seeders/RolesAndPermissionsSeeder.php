<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create Permissions
        $permissions = [
            'view inquiries',
            'create inquiries',
            'edit inquiries',
            'delete inquiries'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission],
                ['guard_name' => 'web']
            );
        }

        // Create Roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['guard_name' => 'web']
        );

        $userRole = Role::firstOrCreate(
            ['name' => 'user'],
            ['guard_name' => 'web']
        );

        // Assign Permissions to Roles
        $adminRole->givePermissionTo(['view inquiries', 'create inquiries', 'edit inquiries', 'delete inquiries']);
        $userRole->givePermissionTo(['view inquiries']);  // Example: User can only view inquiries

        // Optionally, assign roles to a user (example for the first user)
        $user = \App\Models\User::first(); // Make sure you have a user in the database
        if ($user) {
            $user->assignRole('user');
        }
    }
}
