<?php

use App\Presentation\API\Category\Controllers\CategoryController;
use App\Presentation\API\Product\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes - E-commerce
|--------------------------------------------------------------------------
| Read-only routes accessible without authentication
*/

// Products
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);

// Categories
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('root', [CategoryController::class, 'rootCategories'])->name('root');
    Route::get('{id}', [CategoryController::class, 'show']);
});
