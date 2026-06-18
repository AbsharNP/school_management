<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    /**
     * WHAT layer: does the user hold the action permission?
     * WHICH layer: if they're a teacher, is the student in their class group?
     *
     * Super Admin is short-circuited to `true` by Gate::before in AppServiceProvider,
     * so it never reaches these methods.
     */

    public function viewAny(User $user): bool
    {
        // "student-list" = WHAT. Controller query applies the WHICH (class-group) filter.
        return $user->can('student-list');
    }

    public function view(User $user, Student $student): bool
    {
        // A Student may only ever see their own record.
        if ($user->hasRole('Student')) {
            return $user->id === $student->user_id;
        }

        // WHAT: must hold student-list. WHICH: teachers are scoped to their class group.
        if ($user->can('student-list')) {
            return $this->inScope($user, $student);
        }

        return false;
    }

    public function create(User $user): bool
    {
        // WHAT only — the controller forces the new student into the teacher's
        // own class group, so there's no cross-group record to check yet.
        return $user->can('student-create');
    }

    public function update(User $user, Student $student): bool
    {
        // WHAT: student-edit. WHICH: must be in the teacher's class group.
        return $user->can('student-edit') && $this->inScope($user, $student);
    }

    public function delete(User $user, Student $student): bool
    {
        // Teachers are not granted student-delete, so only Super Admin (via Gate::before)
        // or a role explicitly given this permission can delete.
        return $user->can('student-delete');
    }

    /**
     * WHICH layer: a teacher may only touch students inside their own class group.
     * Non-teachers with the permission (e.g. an admin role) are not class-scoped.
     */
    private function inScope(User $user, Student $student): bool
    {
        if ($user->teacher) {
            return $user->teacher->class_group_id === $student->class_group_id;
        }

        return true;
    }
}
