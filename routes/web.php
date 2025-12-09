<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);
Route::get('/about', [AboutController::class, 'index']);
Route::get('/contact', [ContactController::class, 'index']);
Route::get('/catalog', [ProductController::class, 'page']);

// Admin Routes
Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

