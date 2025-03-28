<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AssignPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * */
    public function run()
    {
        // Find or create the permission
        $permission = Permission::findOrCreate('view inquiries');

        // Provide a valid business_id
        $businessId = 1; // Replace with an appropriate value

        // Check if a user exists; if not, create one
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'], // Ensure email is unique
            [
                'name' => 'Admin', // Default user name
                'password' => bcrypt('password'), // Default user password
                'business_id' => $businessId, // Add the business_id
                'role_id' => 1, // Optional: Add a default role_id if required
                'status' => 1, // Optional: Active status
            ]
        );

        // Assign the permission to the user
        $user->givePermissionTo($permission);

        echo "Permission assigned successfully to {$user->email}.\n";
    }
}
