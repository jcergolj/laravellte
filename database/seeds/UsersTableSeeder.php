<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'email' => 'admin@lte.com',
            'role_id' => Role::whereName('admin')->first(),
        ]);

        factory(User::class)->create([
            'email' => 'manager@lte.com',
            'role_id' => Role::whereName('manager')->first(),
        ]);

        for ($i = 1; $i < 10; $i++) {
            factory(User::class)->create([
                'role_id' => Role::whereName('manager')->first(),
            ]);
        }
    }
}
