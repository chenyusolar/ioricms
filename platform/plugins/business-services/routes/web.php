<?php

use Botble\Base\Facades\AdminHelper;
use Botble\BusinessService\Http\Controllers\PackageController;
use Botble\BusinessService\Http\Controllers\PublicController;
use Botble\BusinessService\Http\Controllers\ServiceCategoryController;
use Botble\BusinessService\Http\Controllers\ServiceController;
use Botble\BusinessService\Models\Package;
use Botble\BusinessService\Models\Service;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::middleware('auth')->prefix('business-services')->name('business-services.')->group(function (): void {
        Route::group(['prefix' => 'service-categories', 'as' => 'service-categories.'], function (): void {
            Route::resource('', ServiceCategoryController::class)
                ->parameters(['' => 'service_category']);
        });


        Route::group(['prefix' => 'services', 'as' => 'services.'], function (): void {
            Route::resource('', ServiceController::class)
                ->parameters(['' => 'service']);
        });

        Route::group(['prefix' => 'packages', 'as' => 'packages.'], function (): void {
            Route::resource('', PackageController::class)
                ->parameters(['' => 'package']);
        });
    });
});

Theme::registerRoutes(function (): void {
    Route::get(
        sprintf('%s/{slug}', SlugHelper::getPrefix(Service::class, 'services')),
        [PublicController::class, 'service']
    )
        ->name('public.service');

    Route::get(
        sprintf('%s/{slug}', SlugHelper::getPrefix(Package::class, 'packages')),
        [PublicController::class, 'package']
    )
        ->name('public.package');
});
