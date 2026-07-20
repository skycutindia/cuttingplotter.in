<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductBulkController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::post('products/{id}/clone', [ProductController::class, 'clone'])->name('products.clone');
    Route::get('products-bulk', [ProductBulkController::class, 'index'])->name('products.bulk');
    Route::get('products-export', [ProductBulkController::class, 'export'])->name('products.export');
    Route::post('products-export-selected', [ProductBulkController::class, 'exportSelected'])->name('products.export-selected');
    Route::post('products-import', [ProductBulkController::class, 'import'])->name('products.import');
    Route::get('products-template', [ProductBulkController::class, 'template'])->name('products.template');
    Route::post('products-bulk-update', [ProductBulkController::class, 'bulkUpdate'])->name('products.bulk-update');

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

    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::post('media', [MediaController::class, 'store'])->name('media.store');
    Route::put('media/{medium}', [MediaController::class, 'update'])->name('media.update');
    Route::post('media/{medium}/replace', [MediaController::class, 'replace'])->name('media.replace');
    Route::delete('media/{medium}', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::delete('media-bulk', [MediaController::class, 'destroyBulk'])->name('media.bulk-destroy');
    Route::post('media/folders', [MediaController::class, 'storeFolder'])->name('media.folders.store');
    Route::get('media/picker', [MediaController::class, 'picker'])->name('media.picker');

    Route::resource('leads', LeadController::class)->only(['index', 'show', 'update', 'destroy']);

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('seo', [SeoController::class, 'index'])->name('seo.index');
    Route::put('seo/global', [SeoController::class, 'updateGlobalSeo'])->name('seo.global');
    Route::put('seo/robots', [SeoController::class, 'updateRobots'])->name('seo.robots');
    Route::post('seo/redirects', [SeoController::class, 'storeRedirect'])->name('seo.redirects.store');
    Route::put('seo/redirects/{redirect}', [SeoController::class, 'updateRedirect'])->name('seo.redirects.update');
    Route::delete('seo/redirects/{redirect}', [SeoController::class, 'destroyRedirect'])->name('seo.redirects.destroy');
    Route::post('seo/sitemap/clear', [SeoController::class, 'clearSitemapCache'])->name('seo.sitemap.clear');
});
