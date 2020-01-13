<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Role::class)->create([
            'name' => 'admin',
            'label' => 'admin',
        ]);

        factory(Role::class)->create([
            'name' => 'manager',
            'label' => 'manager',
        ]);
    }
}
