<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\UserController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::get('/profile', [AuthController::class, 'profile']);

});

// Admin Routes
Route::middleware(['auth:api', 'role:admin'])->group(function () {

    Route::get('/admin-dashboard', function () {
        return response()->json([
            'message' => 'Welcome Admin'
        ]);
    });

     Route::apiResource('warehouses', WarehouseController::class);
     Route::apiResource('categories', CategoryController::class);
     Route::apiResource('products', ProductController::class);
     Route::post('/stock-in', [StockMovementController::class, 'stockIn']);
     Route::post('/stock-out', [StockMovementController::class, 'stockOut']);
     Route::get('/stock-history', [StockMovementController::class, 'history']);

});

// Warehouse Manager Routes
Route::middleware(['auth:api', 'role:warehouse_manager'])->group(function () {

    Route::get('/manager-dashboard', function () {
        return response()->json([
            'message' => 'Welcome Warehouse Manager'
        ]);
    });

    Route::get('/my-warehouse', [WarehouseController::class, 'myWarehouse']);
    Route::get('/my-categories', [CategoryController::class, 'myCategories']);
    Route::get('/my-products', [ProductController::class, 'myProducts']);
    Route::post('/my-stock-in', [StockMovementController::class, 'stockIn']);
    Route::post('/my-stock-out', [StockMovementController::class, 'stockOut']);

});

// User Routes
Route::middleware(['auth:api', 'role:user'])->group(function () {

    Route::get('/user-dashboard', function () {
        return response()->json([
            'message' => 'Welcome User'
        ]);
    });

    Route::get('/user-warehouses', [UserController::class, 'warehouses']);

    Route::get('/user-warehouses/{warehouseId}', [UserController::class, 'warehouseDetails']);

    Route::get('/user-warehouses/{warehouseId}/categories', [UserController::class, 'warehouseCategories']);

    Route::get('/user-warehouses/{warehouseId}/products', [UserController::class, 'warehouseProducts']);

});