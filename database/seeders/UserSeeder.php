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
        $hr = Department::where('name', 'Human Resources')->firstOrFail();

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@ticketing.test',
            'department_id' => $it->id,
        ]);
        $admin->assignRole('Admin');

        $agent1 = User::factory()->create([
            'name' => 'Agent Satu',
            'email' => 'agent1@ticketing.test',
            'department_id' => $it->id,
        ]);
        $agent1->assignRole('Agent');

        $agent2 = User::factory()->create([
            'name' => 'Agent Dua',
            'email' => 'agent2@ticketing.test',
            'department_id' => $it->id,
        ]);
        $agent2->assignRole('Agent');

        $requester1 = User::factory()->create([
            'name' => 'Requester Satu',
            'email' => 'requester1@ticketing.test',
            'department_id' => $hr->id,
        ]);
        $requester1->assignRole('Requester');

        $requester2 = User::factory()->create([
            'name' => 'Requester Dua',
            'email' => 'requester2@ticketing.test',
            'department_id' => $hr->id,
        ]);
        $requester2->assignRole('Requester');
    }
}
