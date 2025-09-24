<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Permissions
        Permission::create(['name' => 'create-ticket']);
        Permission::create(['name' => 'view-own-tickets']);
        Permission::create(['name' => 'view-assigned-tickets']);
        Permission::create(['name' => 'update-ticket-status']);
        Permission::create(['name' => 'view-all-tickets']);
        Permission::create(['name' => 'manage-users']);
        Permission::create(['name' => 'view-reports']);
        Permission::create(['name' => 'view-activity-log']);

        // Cari Roles yang sudah ada
        $adminRole = Role::findByName('Admin');
        $analystRole = Role::findByName('Analyst');
        $userRole = Role::findByName('User');

        // Berikan Permissions ke Roles
        $userRole->givePermissionTo([
            'create-ticket',
            'view-own-tickets',
        ]);

        $analystRole->givePermissionTo([
            'view-assigned-tickets',
            'update-ticket-status',
            'view-all-tickets', // Analyst juga bisa melihat semua tiket
        ]);

        // Admin mendapatkan semua permission
        $adminRole->givePermissionTo(Permission::all());
    }
}
