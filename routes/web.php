<?php

use App\Http\Controllers\Auth\SessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:web')->group(function (): void {
    Route::get('/login', [SessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [SessionController::class, 'store'])
        ->name('login.store');
});

Route::middleware('auth:web')->group(function (): void {
    Route::view('/', 'home')->name('home');

    Route::post('/logout', [SessionController::class, 'destroy'])
        ->name('logout');
});
