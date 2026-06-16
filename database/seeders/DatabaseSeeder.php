<?php

namespace Database\Seeders;

use App\Models\ClassGroup;
use App\Models\Role;
use App\Models\Standard;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Permissions ───────────────────────────────────────────────────────
        $permissions = [
            'role-list', 'role-create', 'role-edit', 'role-delete',
            'user-list', 'user-create', 'user-edit', 'user-delete',
            'student-list', 'student-create', 'student-edit', 'student-delete',
            'class-list', 'class-create', 'class-edit', 'class-delete',
        ];

        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        // ── Roles ─────────────────────────────────────────────────────────────
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $primaryTeacher = Role::create(['name' => 'Primary Teacher']);
        $primaryTeacher->givePermissionTo(['student-list', 'student-create', 'student-edit']);

        $highSchoolTeacher = Role::create(['name' => 'High School Teacher']);
        $highSchoolTeacher->givePermissionTo(['student-list', 'student-create', 'student-edit']);

        Role::create(['name' => 'Student']);

        // ── Super Admin user ──────────────────────────────────────────────────
        $superAdminUser = User::factory()->create([
            'name'     => 'Super Admin',
            'email'    => 'sadmin@admin.com',
            'password' => Hash::make('suad123'),
        ]);
        $superAdminUser->assignRole('Super Admin');

        // ── Class Groups ──────────────────────────────────────────────────────
        $primaryGrade1   = ClassGroup::create(['name' => 'Primary - Grade 1']);
        $primaryGrade2   = ClassGroup::create(['name' => 'Primary - Grade 2']);
        $highSchoolForm1 = ClassGroup::create(['name' => 'High School - Form 1']);
        $highSchoolForm2 = ClassGroup::create(['name' => 'High School - Form 2']);

        // ── Standards (classes within each group) ─────────────────────────────
        $pg1a = Standard::create(['name' => 'Grade 1A', 'classgroup_id' => $primaryGrade1->id]);
        $pg1b = Standard::create(['name' => 'Grade 1B', 'classgroup_id' => $primaryGrade1->id]);

        $pg2a = Standard::create(['name' => 'Grade 2A', 'classgroup_id' => $primaryGrade2->id]);
        $pg2b = Standard::create(['name' => 'Grade 2B', 'classgroup_id' => $primaryGrade2->id]);

        $hs1a = Standard::create(['name' => 'Form 1A', 'classgroup_id' => $highSchoolForm1->id]);
        $hs1b = Standard::create(['name' => 'Form 1B', 'classgroup_id' => $highSchoolForm1->id]);

        $hs2a = Standard::create(['name' => 'Form 2A', 'classgroup_id' => $highSchoolForm2->id]);
        $hs2b = Standard::create(['name' => 'Form 2B', 'classgroup_id' => $highSchoolForm2->id]);

        // ── Teachers ──────────────────────────────────────────────────────────
        $aliceUser = User::factory()->create([
            'name'     => 'Alice Johnson',
            'email'    => 'alice@school.com',
            'password' => Hash::make('ali123'),
        ]);
        $aliceUser->assignRole('Primary Teacher');
        $alice = Teacher::create([
            'name'           => 'Alice Johnson',
            'email'          => 'alice@school.com',
            'subject'        => 'Mathematics',
            'class_group_id' => $primaryGrade1->id,
            'user_id'        => $aliceUser->id,
        ]);

        $bobUser = User::factory()->create([
            'name'     => 'Bob Smith',
            'email'    => 'bob@school.com',
            'password' => Hash::make('bob123'),
        ]);
        $bobUser->assignRole('Primary Teacher');
        $bob = Teacher::create([
            'name'           => 'Bob Smith',
            'email'          => 'bob@school.com',
            'subject'        => 'English',
            'class_group_id' => $primaryGrade2->id,
            'user_id'        => $bobUser->id,
        ]);

        $carolUser = User::factory()->create([
            'name'     => 'Carol White',
            'email'    => 'carol@school.com',
            'password' => Hash::make('car123'),
        ]);
        $carolUser->assignRole('High School Teacher');
        $carol = Teacher::create([
            'name'           => 'Carol White',
            'email'          => 'carol@school.com',
            'subject'        => 'Physics',
            'class_group_id' => $highSchoolForm1->id,
            'user_id'        => $carolUser->id,
        ]);

        $davidUser = User::factory()->create([
            'name'     => 'David Lee',
            'email'    => 'david@school.com',
            'password' => Hash::make('dav123'),
        ]);
        $davidUser->assignRole('High School Teacher');
        $david = Teacher::create([
            'name'           => 'David Lee',
            'email'          => 'david@school.com',
            'subject'        => 'Chemistry',
            'class_group_id' => $highSchoolForm2->id,
            'user_id'        => $davidUser->id,
        ]);

        // Assign head teachers to class groups
        $primaryGrade1->update(['head_teacher_id' => $alice->id]);
        $primaryGrade2->update(['head_teacher_id' => $bob->id]);
        $highSchoolForm1->update(['head_teacher_id' => $carol->id]);
        $highSchoolForm2->update(['head_teacher_id' => $david->id]);

        // ── Students ──────────────────────────────────────────────────────────
        $students = [
            ['name' => 'Charlie Brown',  'email' => 'charlie@school.com', 'admission_no' => 'ADM001', 'password' => 'cha123', 'class_group_id' => $primaryGrade1->id,   'class_id' => $pg1a->id],
            ['name' => 'Diana Prince',   'email' => 'diana@school.com',   'admission_no' => 'ADM002', 'password' => 'dia123', 'class_group_id' => $primaryGrade1->id,   'class_id' => $pg1b->id],
            ['name' => 'Ethan Hunt',     'email' => 'ethan@school.com',   'admission_no' => 'ADM003', 'password' => 'eth123', 'class_group_id' => $primaryGrade2->id,   'class_id' => $pg2a->id],
            ['name' => 'Fiona Green',    'email' => 'fiona@school.com',   'admission_no' => 'ADM004', 'password' => 'fio123', 'class_group_id' => $primaryGrade2->id,   'class_id' => $pg2b->id],
            ['name' => 'George Clark',   'email' => 'george@school.com',  'admission_no' => 'ADM005', 'password' => 'geo123', 'class_group_id' => $highSchoolForm1->id, 'class_id' => $hs1a->id],
            ['name' => 'Hannah Adams',   'email' => 'hannah@school.com',  'admission_no' => 'ADM006', 'password' => 'han123', 'class_group_id' => $highSchoolForm1->id, 'class_id' => $hs1b->id],
            ['name' => 'Ivan Torres',    'email' => 'ivan@school.com',    'admission_no' => 'ADM007', 'password' => 'iva123', 'class_group_id' => $highSchoolForm2->id, 'class_id' => $hs2a->id],
            ['name' => 'Julia Roberts',  'email' => 'julia@school.com',   'admission_no' => 'ADM008', 'password' => 'jul123', 'class_group_id' => $highSchoolForm2->id, 'class_id' => $hs2b->id],
        ];

        foreach ($students as $data) {
            $studentUser = User::factory()->create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $studentUser->assignRole('Student');

            Student::create([
                'name'           => $data['name'],
                'email'          => $data['email'],
                'admission_no'   => $data['admission_no'],
                'class_group_id' => $data['class_group_id'],
                'class_id'       => $data['class_id'],
                'user_id'        => $studentUser->id,
            ]);
        }
    }
}
