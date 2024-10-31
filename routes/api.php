<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\MainCategoriesController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\SubCategoriesController;
use App\Http\Controllers\Admin\UnitTypeController;
use App\Http\Controllers\Admin\WarehouseController;
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

    /* unit type */
    Route::apiResource('unitType', UnitTypeController::class);
});

/* set middleware 'middleware' => ['auth:sanctum', 'admin']] */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth:sanctum']], function () {

    /* office */
    Route::get('get-office', [OfficeController::class, 'getOffices'])->name('get-office');
    Route::apiResource('office', OfficeController::class);

    /* warehouse */
    Route::apiResource('warehouse', WarehouseController::class);

    /* MainCategories */
    Route::apiResource('MainCategory', MainCategoriesController::class);

    /* Subcategory  */
    Route::apiResource('Subcategory', SubCategoriesController::class);
});
