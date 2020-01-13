<?php

use App\Models\Permission;
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
        factory(Permission::class)->create([
            'group' => 'users',
            'name' => 'users.index',
            'description' => 'View Users',
        ]);

        factory(Permission::class)->create([
            'group' => 'users',
            'name' => 'users.store',
            'description' => 'Create New User',
        ]);

        factory(Permission::class)->create([
            'group' => 'users',
            'name' => 'users.update',
            'description' => 'Edit Existing User',
        ]);

        factory(Permission::class)->create([
            'group' => 'users',
            'name' => 'users.destroy',
            'description' => 'Delete Existing User',
        ]);

        factory(Permission::class)->create([
            'group' => 'roles',
            'name' => 'roles.index',
            'description' => 'View Roles',
        ]);

        factory(Permission::class)->create([
            'group' => 'roles',
            'name' => 'roles.store',
            'description' => 'Create New Role',
        ]);

        factory(Permission::class)->create([
            'group' => 'roles',
            'name' => 'roles.update',
            'description' => 'Edit Existing Role',
        ]);

        factory(Permission::class)->create([
            'group' => 'roles',
            'name' => 'roles.destroy',
            'description' => 'Delete Exiting Role',
        ]);
    }
}
