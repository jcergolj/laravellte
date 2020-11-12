<?php

namespace Database\Seeders;

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
    }
}
