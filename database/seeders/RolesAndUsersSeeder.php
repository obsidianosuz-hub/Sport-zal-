<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $managerRole = Role::create(['name' => 'manager']);
        $trainerRole = Role::create(['name' => 'trainer']);
        $cookRole = Role::create(['name' => 'cook']);

        $admin = User::create([
            'name' => 'Asosiy Admin',
            'phone' => '+998901234567',
            'pin_code' => '7777',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $admin->assignRole($adminRole);

        $manager = User::create([
            'name' => 'Menejer',
            'phone' => '+998901111111',
            'pin_code' => '8888',
            'password' => null,
            'is_active' => true,
        ]);
        $manager->assignRole($managerRole);

        $trainer = User::create([
            'name' => 'Treyner',
            'phone' => '+998902222222',
            'pin_code' => '9999',
            'password' => null,
            'is_active' => true,
        ]);
        $trainer->assignRole($trainerRole);

        $cook = User::create([
            'name' => 'Oshpaz',
            'phone' => '+998903333333',
            'pin_code' => '1010',
            'password' => null,
            'is_active' => true,
        ]);
        $cook->assignRole($cookRole);
    }
}
