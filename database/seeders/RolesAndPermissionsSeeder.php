<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 🔹 2. (Optional) Create permissions
        // Permission::create(['name' => 'edit articles']);
        // Permission::create(['name' => 'delete articles']);
        // Permission::create(['name' => 'view students']);

        // 🔹 3. Create roles
        $adminRole = Role::create(['name' => 'Admin']);
        $teacherRole = Role::create(['name' => 'Teacher']);
        $parentRole = Role::create(['name' => 'Parent']);
        $studentRole = Role::create(['name' => 'Student']);
    }
}
