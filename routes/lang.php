<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('/greeting/{locale}', function (string $locale) {
    if (! in_array($locale, ['en', 'kh'])) {
        abort(400);
    }
    App::setLocale($locale);

    return redirect()->back();
})->name('lang.switch');
