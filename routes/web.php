<?php

use App\Http\Controllers\Auth\SessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\BikeRackController;

Route::middleware('guest:web')->group(function (): void {
    Route::get('/login', [SessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [SessionController::class, 'store'])
        ->name('login.store');
});

Route::middleware('auth:web')->group(function (): void {
    Route::get('/', fn() => redirect()->route('bike-racks.index'))
        ->name('home');

    Route::get('/bike-racks', [BikeRackController::class, 'index'])
        ->name('bike-racks.index');

    Route::post('/logout', [SessionController::class, 'destroy'])
        ->name('logout');
});
