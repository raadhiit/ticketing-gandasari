<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        $all = ['ticket.create', 'ticket.view', 'ticket.edit', 'ticket.assign', 'ticket.close', 'ticket.reopen', 'ticket.comment', 'ticket.comment.internal', 'ticket.delete', 'settings.manage', 'report.view'];

        $created = [];
        foreach ($all as $name) {
            $created[$name] = Permission::findOrCreate($name);
        }

        $requester = Role::create(['name' => 'Requester']);
        $requester->givePermissionTo($created['ticket.create'], $created['ticket.view'], $created['ticket.comment']);

        $agent = Role::create(['name' => 'Agent']);
        $agent->givePermissionTo($created['ticket.create'], $created['ticket.view'], $created['ticket.edit'], $created['ticket.assign'], $created['ticket.close'], $created['ticket.reopen'], $created['ticket.comment'], $created['ticket.comment.internal']);

        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo(...array_values($created));

        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
