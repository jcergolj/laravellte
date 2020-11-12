<?php

namespace Database\Seeders;

use App\Models\Role;
use Database\Factories\UserFactory;
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
        UserFactory::new()->create([
            'email' => 'admin@lte.com',
            'role_id' => Role::whereName('admin')->first(),
        ]);

        UserFactory::new()->create([
            'email' => 'manager@lte.com',
            'role_id' => Role::whereName('manager')->first(),
        ]);

        for ($i = 1; $i < 10; $i++) {
            UserFactory::new()->create([
                'role_id' => Role::whereName('manager')->first(),
            ]);
        }
    }
}
