<?php

use Database\Factories\PermissionFactory;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PermissionFactory::new()->create([
            'group' => 'users',
            'name' => 'users.index',
            'description' => 'View Users',
        ]);

        PermissionFactory::new()->create([
            'group' => 'users',
            'name' => 'users.store',
            'description' => 'Create New User',
        ]);

        PermissionFactory::new()->create([
            'group' => 'users',
            'name' => 'users.update',
            'description' => 'Edit Existing User',
        ]);

        PermissionFactory::new()->create([
            'group' => 'users',
            'name' => 'users.destroy',
            'description' => 'Delete Existing User',
        ]);

        PermissionFactory::new()->create([
            'group' => 'roles',
            'name' => 'roles.index',
            'description' => 'View Roles',
        ]);

        PermissionFactory::new()->create([
            'group' => 'roles',
            'name' => 'roles.store',
            'description' => 'Create New Role',
        ]);

        PermissionFactory::new()->create([
            'group' => 'roles',
            'name' => 'roles.update',
            'description' => 'Edit Existing Role',
        ]);

        PermissionFactory::new()->create([
            'group' => 'roles',
            'name' => 'roles.destroy',
            'description' => 'Delete Exiting Role',
        ]);
    }
}
