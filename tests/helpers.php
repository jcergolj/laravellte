<?php

use Database\Factories\RoleFactory;
use Database\Factories\UserFactory;

/**
 * Admin seeder.
 *
 * @return \App\Models\User
 */
function create_admin()
{
    $role = RoleFactory::new()->create([
        'name' => 'admin',
    ]);

    return UserFactory::new()->create([
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
    $role = RoleFactory::new()->create([
        'name' => 'manager',
    ]);

    return UserFactory::new()->create([
            'email' => 'manager@admin.lte',
            'role_id' => $role->id,
        ]);
}
