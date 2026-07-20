<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::post('products/{id}/clone', [ProductController::class, 'clone'])->name('products.clone');

    Route::resource('brands', BrandController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);

    Route::resource('pages', PageController::class)->except(['show']);
    Route::post('pages/{page}/sections', [PageController::class, 'storeSection'])->name('pages.sections.store');
    Route::put('pages/{page}/sections/{section}', [PageController::class, 'updateSection'])->name('pages.sections.update');
    Route::delete('pages/{page}/sections/{section}', [PageController::class, 'destroySection'])->name('pages.sections.destroy');
    Route::post('pages/{page}/sections/{section}/duplicate', [PageController::class, 'duplicateSection'])->name('pages.sections.duplicate');
    Route::post('pages/{page}/sections/reorder', [PageController::class, 'reorderSections'])->name('pages.sections.reorder');
    Route::post('pages/{page}/sections/{section}/toggle', [PageController::class, 'toggleSection'])->name('pages.sections.toggle');

    Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::post('menus/{menu}/items', [MenuController::class, 'storeItem'])->name('menus.items.store');
    Route::put('menus/{menu}/items/{item}', [MenuController::class, 'updateItem'])->name('menus.items.update');
    Route::delete('menus/{menu}/items/{item}', [MenuController::class, 'destroyItem'])->name('menus.items.destroy');
    Route::post('menus/{menu}/reorder', [MenuController::class, 'reorderItems'])->name('menus.reorder');

    Route::resource('banners', BannerController::class)->except(['show']);

    Route::resource('leads', LeadController::class)->only(['index', 'show', 'update', 'destroy']);

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});
