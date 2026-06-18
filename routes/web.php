<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ClassGroupController;
use App\Http\Controllers\StandardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('pages.auth.login', ['title' => 'Login']);
    })->name('login');

    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('pages.dashboard.dashboard', ['title' => 'Dashboard']);
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile — all authenticated users
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Roles
    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:role-list')->name('roles.index');
    Route::get('/roles/{id}', [RoleController::class, 'show'])->middleware('permission:role-list')->name('roles.show');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:role-create')->name('roles.store');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->middleware('permission:role-edit')->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->middleware('permission:role-delete')->name('roles.destroy');

    // Permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:permission-list')->name('permissions.index');
    Route::get('/permissions/{id}', [PermissionController::class, 'show'])->middleware('permission:permission-list')->name('permissions.show');
    Route::post('/permissions', [PermissionController::class, 'store'])->middleware('permission:permission-create')->name('permissions.store');
    Route::put('/permissions/{id}', [PermissionController::class, 'update'])->middleware('permission:permission-edit')->name('permissions.update');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->middleware('permission:permission-delete')->name('permissions.destroy');

    // Users
    Route::get('/users', [UserController::class, 'index'])->middleware('permission:user-list')->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware('permission:user-list')->name('users.show');
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:user-create')->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->middleware('permission:user-edit')->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('permission:user-delete')->name('users.destroy');

    // Class Groups
    Route::get('/class-groups', [ClassGroupController::class, 'index'])->middleware('permission:classgroup-list')->name('class-groups.index');
    Route::get('/class-groups/{id}', [ClassGroupController::class, 'show'])->middleware('permission:classgroup-list')->name('class-groups.show');
    Route::post('/class-groups', [ClassGroupController::class, 'store'])->middleware('permission:classgroup-create')->name('class-groups.store');
    Route::put('/class-groups/{id}', [ClassGroupController::class, 'update'])->middleware('permission:classgroup-edit')->name('class-groups.update');
    Route::delete('/class-groups/{id}', [ClassGroupController::class, 'destroy'])->middleware('permission:classgroup-delete')->name('class-groups.destroy');

    // Classes (Standards)
    Route::get('/classes', [StandardController::class, 'index'])->middleware('permission:class-list')->name('class.index');
    Route::get('/classes/{id}', [StandardController::class, 'show'])->middleware('permission:class-list')->name('class.show');
    Route::post('/classes', [StandardController::class, 'store'])->middleware('permission:class-create')->name('class.store');
    Route::put('/classes/{id}', [StandardController::class, 'update'])->middleware('permission:class-edit')->name('class.update');
    Route::delete('/classes/{id}', [StandardController::class, 'destroy'])->middleware('permission:class-delete')->name('class.destroy');

    // Teachers
    Route::get('/teachers', [TeacherController::class, 'index'])->middleware('permission:teacher-list')->name('teachers.index');
    Route::get('/teachers/{id}', [TeacherController::class, 'show'])->middleware('permission:teacher-list')->name('teachers.show');
    Route::post('/teachers', [TeacherController::class, 'store'])->middleware('permission:teacher-create')->name('teachers.store');
    Route::put('/teachers/{id}', [TeacherController::class, 'update'])->middleware('permission:teacher-edit')->name('teachers.update');
    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->middleware('permission:teacher-delete')->name('teachers.destroy');

    // Students 
    Route::get('/students', [StudentController::class, 'index'])->middleware('permission:student-list')->name('students.index');
    Route::get('/students/{id}', [StudentController::class, 'show'])->middleware('permission:student-list')->name('students.show');
    Route::post('/students', [StudentController::class, 'store'])->middleware('permission:student-create')->name('students.store');
    Route::put('/students/{id}', [StudentController::class, 'update'])->middleware('permission:student-edit')->name('students.update');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->middleware('permission:student-delete')->name('students.destroy');
});
