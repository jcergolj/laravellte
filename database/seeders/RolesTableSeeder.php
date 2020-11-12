<?php

namespace Database\Seeders;

use Database\Factories\RoleFactory;
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
        RoleFactory::new()->create([
            'name' => 'admin',
            'label' => 'admin',
        ]);

        RoleFactory::new()->create([
            'name' => 'manager',
            'label' => 'manager',
        ]);
    }
}
