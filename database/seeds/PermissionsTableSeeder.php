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
            'name' => 'users.create',
            'description' => 'Create New User',
        ]);

        PermissionFactory::new()->create([
            'group' => 'users',
            'name' => 'users.edit',
            'description' => 'Edit Existing User',
        ]);

        PermissionFactory::new()->create([
            'group' => 'users',
            'name' => 'users.delete',
            'description' => 'Delete Existing User',
        ]);

        PermissionFactory::new()->create([
            'group' => 'roles',
            'name' => 'roles.index',
            'description' => 'View Roles',
        ]);

        PermissionFactory::new()->create([
            'group' => 'roles',
            'name' => 'roles.create',
            'description' => 'Create New Role',
        ]);

        PermissionFactory::new()->create([
            'group' => 'roles',
            'name' => 'roles.edit',
            'description' => 'Edit Existing Role',
        ]);

        PermissionFactory::new()->create([
            'group' => 'roles',
            'name' => 'roles.delete',
            'description' => 'Delete Exiting Role',
        ]);
    }
}
