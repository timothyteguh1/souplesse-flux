<?php

namespace Database\Seeders\System;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class SystemPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Permission
        Permission::insert([
            ['id' => '4375dccb-9936-41b6-b003-c9612da1ab65', 'name' => 'superuser', 'guard_name' => 'web'],
        ]);
    }
}
