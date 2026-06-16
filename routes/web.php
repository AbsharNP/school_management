<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ClassGroupController;
use App\Http\Controllers\StandardController;
use App\Http\Controllers\StudentController;
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

    // Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}', [RoleController::class, 'show'])->name('roles.show');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Class Groups
    Route::get('/class-groups', [ClassGroupController::class, 'index'])->name('class-groups.index');
    Route::post('/class-groups', [ClassGroupController::class, 'store'])->name('class-groups.store');
    Route::get('/class-groups/{id}', [ClassGroupController::class, 'show'])->name('class-groups.show');
    Route::put('/class-groups/{id}', [ClassGroupController::class, 'update'])->name('class-groups.update');
    Route::delete('/class-groups/{id}', [ClassGroupController::class, 'destroy'])->name('class-groups.destroy');
    //class
    Route::get('/classes', [StandardController::class, 'index'])->name('class.index');
    Route::post('/classes', [StandardController::class, 'store'])->name('class.store');
    Route::get('/classes/{id}', [StandardController::class, 'show'])->name('class.show');
    Route::put('/classes/{id}', [StandardController::class, 'update'])->name('class.update');
    Route::delete('/classes/{id}', [StandardController::class, 'destroy'])->name('class.destroy');

    // Students
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
