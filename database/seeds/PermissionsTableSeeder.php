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
            'group' => 'user',
            'name' => 'index',
        ]);

        factory(Permission::class)->create([
            'group' => 'user',
            'name' => 'create',
        ]);

        factory(Permission::class)->create([
            'group' => 'user',
            'name' => 'store',
        ]);

        factory(Permission::class)->create([
            'group' => 'role',
            'name' => 'edit',
        ]);

        factory(Permission::class)->create([
            'group' => 'role',
            'name' => 'update',
        ]);

        factory(Permission::class)->create([
            'group' => 'user',
            'name' => 'destroy',
        ]);

        factory(Permission::class)->create([
            'group' => 'role',
            'name' => 'index',
        ]);

        factory(Permission::class)->create([
            'group' => 'role',
            'name' => 'create',
        ]);

        factory(Permission::class)->create([
            'group' => 'role',
            'name' => 'store',
        ]);

        factory(Permission::class)->create([
            'group' => 'role',
            'name' => 'edit',
        ]);

        factory(Permission::class)->create([
            'group' => 'role',
            'name' => 'update',
        ]);

        factory(Permission::class)->create([
            'group' => 'role',
            'name' => 'destroy',
        ]);
    }
}
