<?php

use App\Events\UserLoggedOut;
use App\Livewire\Admin\Auth\VerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'auth.session']], function () {
    Route::get('/email/verify', VerifyEmail::class)->name('verification.notice');

    Route::post('/logout', function () {
        event(new UserLoggedOut(Auth::user()));

        Auth::logout();
        session()->flush();
        session()->invalidate();
        session()->regenerateToken();

        return to_route(_get_homepage_route());
    })->name('logout');

    //    Route::get('/email/verify/{id}/{hash}', EmailVerificationController::class)->name('verification.verify');
});
