<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\App\Http\Controllers\ActivityLogController;
use Modules\Settings\App\Http\Controllers\ConfigurationController;
use Modules\Settings\App\Http\Controllers\LocaleController;
use Modules\Settings\App\Http\Controllers\ModuleController;
use Modules\Settings\App\Http\Controllers\PageController;
use Modules\Settings\App\Http\Controllers\RoleController;
use Modules\Settings\App\Http\Controllers\TranslationController;
use Modules\Settings\App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/get-role-permissions', [UserController::class, 'getRolePermissions'])->name('getRolePermissions');

Route::middleware(['auth', 'sidebar'])->prefix('settings')->name('settings.')->group(function () {
    Route::prefix('module')->name('module.')->group(function () {
        Route::get('/', [ModuleController::class, 'index'])->name('index');
        Route::get('create', [ModuleController::class, 'create'])->name('create');
        Route::post('store', [ModuleController::class, 'store'])->name('store');
        Route::get('/edit/{field_data}', [ModuleController::class, 'edit'])->name('edit');
        // Route::get('/generate/{field_data}', [ModuleController::class, 'generate'])->name('generate');
        Route::post('/update/{field_data}', [ModuleController::class, 'update'])->name('update');
        Route::get('/destroy/{field_data}', [ModuleController::class, 'destroy'])->name('destroy');
        Route::get('/restore/{field_data}', [ModuleController::class, 'restore'])->name('restore');
        Route::get('/exported/{export_to}', [ModuleController::class, 'exported'])->name('export');
    });
    Route::prefix('page')->name('page.')->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('index');
        Route::get('create', [PageController::class, 'create'])->name('create');
        Route::post('store', [PageController::class, 'store'])->name('store');
        Route::get('/edit/{field_data}', [PageController::class, 'edit'])->name('edit');
        Route::post('/update/{field_data}', [PageController::class, 'update'])->name('update');
        Route::get('/destroy/{field_data}', [PageController::class, 'destroy'])->name('destroy');
        Route::get('/restore/{field_data}', [PageController::class, 'restore'])->name('restore');
        Route::get('/exported/{export_to}', [PageController::class, 'exported'])->name('export');
    });
    Route::prefix('role')->name('role.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('create', [RoleController::class, 'create'])->name('create');
        Route::post('store', [RoleController::class, 'store'])->name('store');
        Route::get('/edit/{field_data}', [RoleController::class, 'edit'])->name('edit');
        Route::post('/update/{field_data}', [RoleController::class, 'update'])->name('update');
        Route::get('/destroy/{field_data}', [RoleController::class, 'destroy'])->name('destroy');
        Route::get('/restore/{field_data}', [RoleController::class, 'restore'])->name('restore');
        Route::get('/exported/{export_to}', [RoleController::class, 'exported'])->name('export');
    });
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('create', [UserController::class, 'create'])->name('create');
        Route::post('store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{field_data}', [UserController::class, 'edit'])->name('edit');
        Route::post('/update/{field_data}', [UserController::class, 'update'])->name('update');
        Route::get('/destroy/{field_data}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/restore/{field_data}', [UserController::class, 'restore'])->name('restore');
        Route::get('/exported/{export_to}', [UserController::class, 'exported'])->name('export');
        Route::get('/changePassword/{field_data}', [UserController::class, 'changePassword'])->name('changePassword');
        Route::post('/updatePassword/{field_data}', [UserController::class, 'updatePassword'])->name('updatePassword');

        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::post('/update', [UserController::class, 'updateProfile'])->name('updateProfile');
        Route::match(['get', 'post'], '/resetPassword', [UserController::class, 'resetPassword'])->name('resetPassword');
    });
    Route::prefix('translation')->name('translation.')->group(function () {
        Route::get('/', [TranslationController::class, 'index'])->name('index');
        Route::get('create', [TranslationController::class, 'create'])->name('create');
        Route::post('store', [TranslationController::class, 'store'])->name('store');
        Route::get('/edit/{field_data}', [TranslationController::class, 'edit'])->name('edit');
        Route::post('/update/{field_data}', [TranslationController::class, 'update'])->name('update');
        Route::get('/destroy/{field_data}', [TranslationController::class, 'destroy'])->name('destroy');
        Route::get('/restore/{field_data}', [TranslationController::class, 'restore'])->name('restore');
        Route::get('/exported/{export_to}', [TranslationController::class, 'exported'])->name('export');
        Route::get('/sync', [TranslationController::class, 'sync'])->name('sync');
    });
    Route::prefix('configuration')->name('configuration.')->group(function () {
        Route::get('/', [ConfigurationController::class, 'index'])->name('index');
        Route::get('create', [ConfigurationController::class, 'create'])->name('create');
        Route::post('store', [ConfigurationController::class, 'store'])->name('store');
        Route::get('/edit/{field_data}', [ConfigurationController::class, 'edit'])->name('edit');
        Route::post('/update/{field_data}', [ConfigurationController::class, 'update'])->name('update');
        Route::get('/destroy/{field_data}', [ConfigurationController::class, 'destroy'])->name('destroy');
        Route::get('/restore/{field_data}', [ConfigurationController::class, 'restore'])->name('restore');
        Route::get('/exported/{export_to}', [ConfigurationController::class, 'exported'])->name('export');
        Route::get('/sync', [ConfigurationController::class, 'sync'])->name('sync');
    });
    Route::prefix('locale')->name('locale.')->group(function () {
        Route::get('/', [LocaleController::class, 'index'])->name('index');
        Route::get('create', [LocaleController::class, 'create'])->name('create');
        Route::post('store', [LocaleController::class, 'store'])->name('store');
        Route::get('/edit/{field_data}', [LocaleController::class, 'edit'])->name('edit');
        Route::post('/update/{field_data}', [LocaleController::class, 'update'])->name('update');
        Route::get('/destroy/{field_data}', [LocaleController::class, 'destroy'])->name('destroy');
        Route::get('/restore/{field_data}', [LocaleController::class, 'restore'])->name('restore');
        Route::get('/test/{field_data}', [LocaleController::class, 'restore'])->name('restore');
        Route::get('/exported/{export_to}', [LocaleController::class, 'exported'])->name('export');
    });
    Route::prefix('systemlog')->name('systemlog.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/show-detail', [ActivityLogController::class, 'showDetail'])->name('showDetail');

    });
});
