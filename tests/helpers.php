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
 * @param  $email
 * @return \App\Models\User
 */
function create_user($email = null)
{
    return UserFactory::new()->forRole([
            'name' => 'manager',
        ])->create([
            'email' => $email ?? 'manager@admin.lte',
        ]);
}
