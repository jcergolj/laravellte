<?php

/**
 * Admin seeder.
 *
 * @return \App\Models\User
 */
function create_admin()
{
    $role = factory(\App\Models\Role::class)->create([
        'name' => 'admin',
    ]);

    return factory(\App\Models\User::class)->create([
            'email' => 'admin@admin.lte',
            'role_id' => $role->id,
        ]);
}

/**
 * User seeder.
 *
 * @return \App\Models\User
 */
function create_user()
{
    $role = factory(\App\Models\Role::class)->create([
        'name' => 'manager',
    ]);

    return factory(\App\Models\User::class)->create([
            'email' => 'manager@admin.lte',
            'role_id' => $role->id,
        ]);
}
