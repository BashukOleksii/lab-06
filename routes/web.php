<?php

use App\Http\Controllers\Auth\SessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\BikeRackController;
use App\Http\Controllers\Web\ConfirmationReceiptController;

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

    Route::get('/confirmation-receipts', [ConfirmationReceiptController::class, 'index'])
        ->name('confirmation-receipts.index');

    Route::get('/bike-racks/create', [BikeRackController::class, 'create'])
        ->name('bike-racks.create');

    Route::post('/bike-racks', [BikeRackController::class, 'store'])
        ->name('bike-racks.store');

    Route::get('/bike-racks/{bike_rack}', [BikeRackController::class, 'show'])
        ->whereNumber('bike_rack')
        ->name('bike-racks.show');

    Route::post('/logout', [SessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/bike-racks/{bike_rack}/edit', [BikeRackController::class, 'edit'])
        ->whereNumber('bike_rack')
        ->name('bike-racks.edit');

    Route::patch('/bike-racks/{bike_rack}', [BikeRackController::class, 'update'])
        ->whereNumber('bike_rack')
        ->name('bike-racks.update');

    Route::delete('/bike-racks/{bike_rack}', [BikeRackController::class, 'destroy'])
        ->whereNumber('bike_rack')
        ->name('bike-racks.destroy');

    Route::post('/bike-racks/{bike_rack}/open', [BikeRackController::class, 'open'])
        ->whereNumber('bike_rack')
        ->name('bike-racks.open');
});
