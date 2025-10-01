<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'manage-users',
            'manage-settings',
            'view-reports',
            'manage-site-owners',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $siteOwnerRole = Role::create(['name' => 'site-owner']);

        // Assign permissions to roles
        $adminRole->givePermissionTo($permissions);
        $siteOwnerRole->givePermissionTo(['view-reports']);

        // Create admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'info@rayo.consulting',
        ]);

        $adminUser->assignRole('admin');

        // Create site-owner user
        $siteOwnerUser = User::create([
            'name' => 'Site Owner',
            'email' => 'owner@rayo.consulting',
        ]);

        $siteOwnerUser->assignRole('site-owner');
    }
}