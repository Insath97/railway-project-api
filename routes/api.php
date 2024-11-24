<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\MainCategoriesController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\Product_StockController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\StockInController;
use App\Http\Controllers\Admin\StockOutController;
use App\Http\Controllers\Admin\SubCategoriesController;
use App\Http\Controllers\Admin\UnitTypeController;
use App\Http\Controllers\Admin\UserRolesController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Resources\UserRolesResource;
use Illuminate\Support\Facades\Route;

/* without middleware using */

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    /* handle login */
    Route::post('login', [AuthController::class, 'handlelogin'])->name('handle-login');

    /* logout */
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    /* forgot password */
    Route::post('forgot-password', [AuthController::class, 'sendRestLink'])->name('forgot-password.send');

    /* reset password */
    Route::get('reset-password/{token}', [AuthController::class, 'ResetPassword'])->name('reset-password');
    Route::post('reset-password', [AuthController::class, 'handleResetPassword'])->name('reset-password.send');
});

/* set middleware 'middleware' => ['auth:sanctum', 'admin']] */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:sanctum']], function () {

    /* office */
    Route::get('get-office', [OfficeController::class, 'getOffices'])->name('get-office');
    Route::apiResource('office', OfficeController::class);

    /* warehouse */
    Route::get('get-warehouse/{id}', [WarehouseController::class, 'getWarehouse']);
    Route::apiResource('warehouse', WarehouseController::class);

    /* MainCategories */
    Route::get('get-main-category', [MainCategoriesController::class, 'getMainCategory']);
    Route::apiResource('MainCategory', MainCategoriesController::class);

    /* Subcategory  */
    Route::get('get-sub-category/{id}', [SubCategoriesController::class, 'getSubCategory']);
    Route::apiResource('Subcategory', SubCategoriesController::class);

    /* unit type */
    Route::get('get-unitType', [UnitTypeController::class, 'getUnitType']);
    Route::apiResource('unitType', UnitTypeController::class);

    /* products */
    Route::get('get-product', [ProductsController::class, 'getProducts']);
    Route::apiResource('products', ProductsController::class);

    /* product stocks in or out */
    Route::apiResource('stock-in', StockInController::class);
    Route::apiResource('stock-out', StockOutController::class);

    /* permission  */
    Route::apiResource('permission', PermissionController::class);

    /* roles */
    Route::apiResource('roles', RolesController::class);

    /* users */
    Route::apiResource('users', UserRolesController::class);
});
