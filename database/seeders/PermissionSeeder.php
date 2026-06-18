<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Idempotent: creates the full permission set, grants Super Admin everything,
     * and gives the teacher roles their default access. Safe to re-run on a live DB.
     */
    public function run(): void
    {
        $modules = ['role', 'permission', 'user', 'classgroup', 'class', 'teacher', 'student'];
        $actions = ['list', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name'       => "{$module}-{$action}",
                    'guard_name' => 'web',
                ]);
            }
        }

        Role::firstOrCreate(['name' => 'Super Admin'])
            ->givePermissionTo(Permission::all());

        $teacherDefaults = ['student-list', 'student-create', 'student-edit', 'teacher-list', 'class-list'];
        foreach (['Primary Teacher', 'High School Teacher'] as $name) {
            Role::firstOrCreate(['name' => $name])->givePermissionTo($teacherDefaults);
        }

        Role::firstOrCreate(['name' => 'Student']);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
