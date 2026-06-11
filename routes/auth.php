<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\OneIdAuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\PhoneAuthController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [PhoneAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login/send-code', [PhoneAuthController::class, 'sendLoginCode'])
        ->middleware('throttle:5,1')
        ->name('login.send-code');
    Route::get('login/verify', [PhoneAuthController::class, 'showVerifyForm'])->name('login.verify.form');
    Route::post('login/verify', [PhoneAuthController::class, 'verifyLogin'])
        ->middleware('throttle:10,1')
        ->name('login.verify');

    Route::get('register', [PhoneAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register/send-code', [PhoneAuthController::class, 'sendRegisterCode'])
        ->middleware('throttle:5,1')
        ->name('register.send-code');
    Route::get('register/verify', [PhoneAuthController::class, 'showRegisterVerifyForm'])->name('register.verify.form');
    Route::post('register/verify', [PhoneAuthController::class, 'verifyRegister'])
        ->middleware('throttle:10,1')
        ->name('register.verify');

    Route::get('auth/oneid/redirect', [OneIdAuthController::class, 'redirect'])->name('oneid.redirect');
    Route::get('auth/oneid/callback', [OneIdAuthController::class, 'callback'])->name('oneid.callback');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
