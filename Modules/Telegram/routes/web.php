<?php

use Illuminate\Support\Facades\Route;
use Modules\Telegram\App\Http\Controllers\TelegramController;

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

Route::middleware(['auth', 'sidebar'])->prefix('telegram')->name('telegram.')->group(function () {
    Route::prefix('telegram-bot')->name('telegram-bot.')->group(function () {
        Route::get('/{print}', [TelegramController::class, 'index'])->name('index');
    });
});
