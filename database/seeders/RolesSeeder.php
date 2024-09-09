<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            "admin",
            "manager",
            "user"
        ];
        foreach ($roles as $role) {
            Role::create(["name" => $role]);
        }
        $admin = Role::findByName("admin");
        $user_1 = User::create([
            "name" => "admin",
            "email" => "admin@gmail.com",
            'national_id' => '111222111222',
            'gender' => 'male',
            "password" => '111222111222'
        ]);
        $user_1->assignRole($admin);

        $manager = Role::findByName("manager");
        $user_2 = User::create([
            "name" => "manager",
            "email" => "manager@gmail.com",
            'national_id' => '111333111333',
            'gender' => 'male',
            "password" => '111333111333'
        ]);
        $user_2->assignRole($manager);

        $user = Role::findByName("user");
        $user_3 = User::create([
            "name" => "user",
            "email" => "user@gmail.com",
            'national_id' => '111444111444',
            'gender' => 'female',
            "password" => '111444111444'
        ]);
        $user_3->assignRole($user);


    }
}
