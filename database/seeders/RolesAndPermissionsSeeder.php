<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $admin_role = Role::create(['name' => ADMIN]);
        $editor_role = Role::create(['name' => EDITOR]);
        $reader_role = Role::create(['name' => READER]);

        $manage_users_permission = Permission::create(['name' => MANAGE_USERS]);
        $manage_roles_permission = Permission::create(['name' => MANAGE_ROLES]);
        $manage_courses_permission = Permission::create(['name' => MANAGE_COURSES]);
        $manage_program_permission = Permission::create(['name' => MANAGE_PROGRAMS]);
        $view_course_permission = Permission::create(['name' => VIEW_COURSE]);
        $access_site_stats_permission = Permission::create(['name' => ACCESS_SITE_STATS]);
        $perform_maintenance_functions_permission = Permission::create(['name' => PERFORM_MAINTENANCE_FUNCTIONS]);

        $admin_role->syncPermissions([$manage_users_permission, $manage_courses_permission, $view_course_permission, $access_site_stats_permission, $manage_program_permission, $perform_maintenance_functions_permission, $manage_roles_permission]);

        $editor_role->syncPermissions([$manage_courses_permission, $view_course_permission, $access_site_stats_permission, $manage_program_permission]);

        $reader_role->syncPermissions([$view_course_permission]);
    }
}
