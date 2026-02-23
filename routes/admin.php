<?php

use App\Mail\Verification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\LanguageController;

 // clear cache
 Route::get('clear-cache', function () {
    Artisan::call('optimize:clear');
    return redirect()->back();
})->name('clear-cache');


Route::get('/send-test-email', function () {
    Mail::to('chamnab.roeun.rc@gmail.com', 'CHAMNAB')->send(new Verification());
});

Route::get('change-language/{lang}', [LanguageController::class, 'index'])->name('lang');
