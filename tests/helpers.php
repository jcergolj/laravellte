<?php

use App\Providers\AppServiceProvider;
use Database\Factories\UserFactory;
use Illuminate\Support\Str;

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

/**
 * Random password generator.
 *
 * @return \App\Models\User
 */
function password_generator($length = 16)
{
    return Str::random($length);
}

/**
 * Too short password random password generator.
 *
 * @return \App\Models\User
 */
function too_short_password()
{
    return Str::random(AppServiceProvider::MIN_PASSWORD_LENGTH - 1);
}
