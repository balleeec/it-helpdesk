<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ActivityLogController;


Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// routes/web.php
Route::get('/assets/{path}', function ($path) {
    return response()->file(public_path("assets/{$path}"));
})->where('path', '.*');

Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    // Route untuk Users
    Route::post('users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::get('users/data', [UserController::class, 'data'])->name('users.data');
    Route::resource('users', UserController::class);

    // Route untuk Role & Permission
    Route::get('roles/data', [RoleController::class, 'data'])->name('roles.data');
    Route::resource('roles', RoleController::class);

    // Route untuk Groups
    Route::post('groups/bulk-delete', [GroupController::class, 'bulkDelete'])->name('groups.bulk-delete');
    Route::get('groups/data', [GroupController::class, 'data'])->name('groups.data');
    Route::resource('groups', GroupController::class);

    // Route untuk Kategori
    Route::post('categories/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');
    Route::get('categories/data', [CategoryController::class, 'data'])->name('categories.data');
    Route::resource('categories', CategoryController::class);

    // Route untuk Activity Log
    Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('activity-log/data', [ActivityLogController::class, 'data'])->name('activity-log.data');
});

require __DIR__ . '/auth.php';
