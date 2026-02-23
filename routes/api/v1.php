<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\DbMockup\EndpointController;
use App\Models\DbMockup\Endpoint;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
try {
    $endpoints = Endpoint::all();
    foreach ($endpoints as $endpoint) {
        Route::match([$endpoint->method], $endpoint->endpoint, [EndpointController::class, 'api']);
    }
} catch (\Throwable $th) {
    //throw $th;
}
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::get('payment/webview', [PaymentController::class, 'webView'])->name('webView');
Route::middleware(['auth:sanctum'])->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        // api/v1/auth/logout
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        // api/v1/auth/refresh-token
        Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('refresh-token');
        // api/v1/auth/logout-other-devices
        Route::post('logout-other-devices', [AuthController::class, 'logoutOtherDevices'])->name('logout-other-devices');
        // api/v1/auth/logged-devices
        Route::get('logged-devices', [AuthController::class, 'loggedDevices'])->name('logged-devices');
        // api/v1/auth/change-password
        Route::post('change-password', [AuthController::class, 'changePassword'])->name('change-password');
        // api/v1/auth/forgot-password
        Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
        // api/v1/auth/reset-password
        Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
        // api/v1/auth/update-profile
        Route::post('update-profile', [AuthController::class, 'updateProfile'])->name('update-profile');
        // api/v1/auth/update-profile-picture
        Route::post('update-profile-picture', [AuthController::class, 'updateProfilePicture'])->name('update-profile-picture');
        
    });
    Route::post('payment/create-payment', [PaymentController::class, 'createPayment'])->name('createPayment');
    Route::post('payment/check-payment-status', [PaymentController::class, 'checkPaymentStatus'])->name('checkPaymentStatus');
    Route::post('payment/make-hash', [PaymentController::class, 'makeHash'])->name('makeHash');
});
