<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('pages.auth.login', ['title' => 'Login']);
    })->name('login');

    // Route::get('/signup', function () {
    //     return view('pages.auth.signup', ['title' => 'Sign Up']);
    // })->name('signup');

    // Route::post('/signup', [AuthController::class, 'signup'])->name('signup.submit');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('pages.dashboard.dashboard', ['title' => 'Dashboard']);
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
