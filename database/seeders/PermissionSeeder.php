<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar permissions dengan display name
        $permissions = [
            ['name' => 'view-dashboard', 'display_name' => 'Melihat Dashboard'],
            ['name' => 'create-ticket', 'display_name' => 'Membuat Tiket'],
            ['name' => 'view-own-tickets', 'display_name' => 'Melihat Tiket Sendiri'],
            ['name' => 'view-assigned-tickets', 'display_name' => 'Melihat Tiket yang Ditugaskan'],
            ['name' => 'update-ticket-status', 'display_name' => 'Mengubah Status Tiket'],
            ['name' => 'view-all-tickets', 'display_name' => 'Melihat Semua Tiket'],
            ['name' => 'view-users', 'display_name' => 'Melihat Pengguna'],
            ['name' => 'manage-users', 'display_name' => 'Mengelola Pengguna'],
            ['name' => 'view-role-permission', 'display_name' => 'Melihat Role & Permission'],
            ['name' => 'manage-roles', 'display_name' => 'Mengelola Role'],
            ['name' => 'view-group', 'display_name' => 'Melihat Grup'],
            ['name' => 'manage-groups', 'display_name' => 'Mengelola Grup'],
            ['name' => 'view-category', 'display_name' => 'Melihat Kategori'],
            ['name' => 'manage-categories', 'display_name' => 'Mengelola Kategori'],
            ['name' => 'view-activity-log', 'display_name' => 'Melihat Log Aktivitas'],
            ['name' => 'view-reports', 'display_name' => 'Melihat Laporan'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );
        }

        // Buat roles dengan display name
        $roles = [
            ['name' => 'Admin', 'display_name' => 'Administrator'],
            ['name' => 'Analyst', 'display_name' => 'Analyst'],
            ['name' => 'User', 'display_name' => 'User'],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        // Assign permissions to roles
        $adminRole = Role::findByName('Admin');
        $analystRole = Role::findByName('Analyst');
        $userRole = Role::findByName('User');

        $userRole->givePermissionTo(['create-ticket', 'view-own-tickets']);
        $analystRole->givePermissionTo(['view-own-tickets', 'view-assigned-tickets', 'update-ticket-status', 'view-all-tickets']);
        $adminRole->givePermissionTo(Permission::all());
    }
}
