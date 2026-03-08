<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilterController;


Route::middleware(['auth', 'abilities'])->group(function () {
    Route::get('/get-subcategory', [FilterController::class, 'getSubCategory'])->name('get-subcategory');
});