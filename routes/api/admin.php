<?php

use App\Presentation\API\Auth\Controllers\UserController;
use App\Presentation\API\BankAccount\Controllers\BankAccountController;
use App\Presentation\API\Category\Controllers\CategoryController;
use App\Presentation\API\Department\Controllers\DepartmentController;
use App\Presentation\API\Family\Controllers\FamilyController;
use App\Presentation\API\Product\Controllers\ProductController;
use App\Presentation\API\Role\Controllers\RoleController;
use App\Presentation\API\Tax\Controllers\TaxController;
use App\Presentation\API\Tax\Controllers\TaxTypeController;
use App\Presentation\API\Warehouse\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes - ERP
|--------------------------------------------------------------------------
| Full CRUD operations requiring authentication
| All routes are prefixed with /admin and protected by auth:sanctum
*/

// Users - Full CRUD (routing convencional con agrupación)
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('{id}', [UserController::class, 'show'])->name('show');
    Route::put('{id}', [UserController::class, 'update'])->name('update');
    Route::patch('{id}', [UserController::class, 'update'])->name('update');
    Route::delete('{id}', [UserController::class, 'destroy'])->name('destroy');
});


Route::prefix('departments')->name('departments.')->group(function () {
    Route::get('/', [DepartmentController::class, 'index'])->name('index');
    Route::post('/', [DepartmentController::class, 'store'])->name('store');
    Route::get('/{id}', [DepartmentController::class, 'show'])->name('show');
    Route::put('/{id}', [DepartmentController::class, 'update'])->name('update');

});


Route::prefix('bank-accounts')->name('bank-accounts.')->group(function () {
    Route::get('/', [BankAccountController::class, 'index'])->name('index');
    Route::get('/{id}', [BankAccountController::class, 'show'])->name('show');
    Route::post('/', [BankAccountController::class, 'store'])->name('store');
    Route::put('/{id}', [BankAccountController::class, 'update'])->name('update');
});


// Roles - Full CRUD
Route::apiResource('roles', RoleController::class)->only(['index', 'store']);

// Categories - Full CRUD
Route::apiResource('categories', CategoryController::class);

// Products - Full CRUD
Route::apiResource('products', ProductController::class);

// Taxes - Custom routes (deben ir ANTES del apiResource)
Route::patch('taxes/{taxId}/active', [TaxController::class, 'updateActive']);

// Taxes - Full CRUD (solo métodos implementados)
Route::apiResource('taxes', TaxController::class)->only(['index', 'store', 'show', 'update']);
Route::apiResource('tax-categories', TaxTypeController::class);


// Families - Full CRUD (solo métodos implementados)
Route::patch('families/{familyId}/active', [FamilyController::class, 'updateActive']);
Route::apiResource('families', FamilyController::class)->only(['index', 'store', 'show', 'update']);

// Warehouses - Full CRUD (solo métodos implementados)
Route::apiResource('warehouses', WarehouseController::class)->only(['index', 'store', 'show', 'update']);
