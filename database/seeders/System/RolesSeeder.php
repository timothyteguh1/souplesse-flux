<?php

namespace Database\Seeders\System;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'Owner',
        ])->syncPermissions(['superuser']);

        Role::create([
            'name' => 'Staff',
        ]);
    }
}
