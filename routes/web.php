<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// web.php
Route::view('/', 'auth.login')->name('login');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
