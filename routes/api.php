<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Presentation\API\Category\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rutas divididas en:
| 1. Rutas Públicas - Para E-commerce (sin autenticación)
| 2. Rutas Privadas - Para Admin (requieren autenticación)
|
*/

// ============================================================================
// RUTAS PÚBLICAS - E-COMMERCE
// ============================================================================
// Estas rutas NO requieren autenticación
// Accesibles desde la app de e-commerce para clientes

Route::prefix('public')->group(function () {

    // Categories - Solo lectura
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])
            ->name('public.categories.index');
        Route::get('/root', [CategoryController::class, 'rootCategories'])
            ->name('public.categories.root');
        Route::get('/{id}', [CategoryController::class, 'show'])
            ->name('public.categories.show');
    });


});


// ============================================================================
// RUTAS PRIVADAS - ADMIN PANEL
// ============================================================================
// Estas rutas REQUIEREN autenticación con Sanctum
// Solo accesibles desde la app de administración

Route::middleware('auth:sanctum')->group(function () {

    // User - Obtener usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('admin.user');

    // Auth - Logout
    // Route::post('/logout', [AuthController::class, 'logout']);

    // Categories - CRUD completo (Admin)
    Route::prefix('admin/categories')->group(function () {
        Route::post('/', [CategoryController::class, 'store'])
            ->name('admin.categories.store');
        Route::put('/{id}', [CategoryController::class, 'update'])
            ->name('admin.categories.update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])
            ->name('admin.categories.destroy');

        // También puede ver (útil para el admin)
        Route::get('/', [CategoryController::class, 'index'])
            ->name('admin.categories.index');
    });


});
