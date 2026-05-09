<?php

namespace Database\Seeders\System;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Denny Chandra',
            'username' => 'denny',
            'email' => null,
            'password' => Hash::make('chandra'),
            'email_verified_at' => now(),
            'is_active' => true,
            'is_developer' => true,
        ]);

        User::create([
            'name' => 'Owner',
            'username' => 'owner',
            'email' => null,
            'password' => Hash::make('owner'),
            'email_verified_at' => now(),
            'is_active' => true,
        ])->assignRole(['Owner']);

        User::create([
            'name' => 'Staff',
            'username' => 'staff',
            'email' => null,
            'password' => Hash::make('staff'),
            'email_verified_at' => now(),
            'is_active' => true,
        ])->assignRole(['Staff']);
    }
}
