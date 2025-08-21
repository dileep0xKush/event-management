<?php

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// web.php
Route::view('/', 'auth.login')->name('login');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/admin/categories', [CategoryController::class, 'viewPage']);
