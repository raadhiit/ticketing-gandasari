<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $it = Department::where('name', 'Information Technology')->firstOrFail();

        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@ticketing.test',
            'password' => bcrypt('password'),
            'department_id' => $it->id,
        ]);
        $admin->assignRole('superadmin');

        $agent1 = User::create([
            'name' => 'IT ERP Satu',
            'email' => 'it-erp1@ticketing.test',
            'password' => bcrypt('password'),
            'department_id' => $it->id,
        ]);
        $agent1->assignRole('IT ERP');

        $agent2 = User::create([
            'name' => 'IT ERP Dua',
            'email' => 'it-erp2@ticketing.test',
            'password' => bcrypt('password'),
            'department_id' => $it->id,
        ]);
        $agent2->assignRole('IT ERP');

        User::create([
            'name' => 'User Satu',
            'email' => 'user1@ticketing.test',
            'password' => bcrypt('password'),
            'department_id' => $it->id,
        ])->assignRole('User');

        User::create([
            'name' => 'User Dua',
            'email' => 'user2@ticketing.test',
            'password' => bcrypt('password'),
            'department_id' => $it->id,
        ])->assignRole('User');
    }
}
