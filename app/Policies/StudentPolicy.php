<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Primary Teacher', 'High School Teacher']);
    }

    public function view(User $user, Student $student): bool
    {
        if ($user->hasRole('Super Admin')) return true;

        if ($user->hasAnyRole(['Primary Teacher', 'High School Teacher'])) {
            return $user->teacher?->class_group_id === $student->class_group_id;
        }

        if ($user->hasRole('Student')) {
            return $user->id === $student->user_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Primary Teacher', 'High School Teacher']);
    }

    public function update(User $user, Student $student): bool
    {
        if ($user->hasRole('Super Admin')) return true;

        if ($user->hasAnyRole(['Primary Teacher', 'High School Teacher'])) {
            return $user->teacher?->class_group_id === $student->class_group_id;
        }

        return false;
    }

    public function delete(User $user, Student $student): bool
    {
        return $user->hasRole('Super Admin');
    }
}
