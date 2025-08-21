<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\EventController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// web.php
Route::view('/', 'auth.login')->name('login');


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/admin/categories', [CategoryController::class, 'viewPage'])->name('categories');
Route::get('/admin/events', [EventController::class, 'viewPage'])->name('events');
