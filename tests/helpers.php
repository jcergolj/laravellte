<?php

use Database\Factories\UserFactory;

/**
 * Admin seeder.
 *
 * @return \App\Models\User
 */
function create_admin()
{
    return UserFactory::new()->forRole([
            'name' => 'admin',
        ])->create([
            'email' => 'admin@admin.lte',
        ]);
}

/**
 * User seeder.
 *
 * @return \App\Models\User
 */
function create_user()
{
    return UserFactory::new()->forRole([
            'name' => 'manager',
        ])->create([
            'email' => 'manager@admin.lte',
        ]);
}
