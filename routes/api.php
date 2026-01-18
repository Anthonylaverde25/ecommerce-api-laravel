<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Organized by Access Level
|--------------------------------------------------------------------------
| 
| Routes are split into separate files for better organization:
| - routes/api/auth.php    → Authentication (public & protected)
| - routes/api/public.php  → E-commerce read-only (no auth)
| - routes/api/admin.php   → ERP full CRUD (auth required)
|
*/

// Auth routes (public + protected)
Route::prefix('auth')->group(base_path('routes/api/auth.php'));

// Public routes (e-commerce)
Route::prefix('public')->name('public.')->group(base_path('routes/api/public.php'));

// Admin routes (ERP - protected)
Route::prefix('admin')->name('admin.')->middleware('auth:sanctum')->group(base_path('routes/api/admin.php'));
