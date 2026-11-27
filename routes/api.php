<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BikeRackController;
use App\Http\Controllers\Api\ConfirmationReceiptController;

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:5,1,api-register')
    ->name('api.register');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1,api-login')
    ->name('api.login');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->name('api.')->group(function (): void {
    Route::get('/districts', [BikeRackController::class, 'districts'])
        ->name('districts.index');

    Route::get('/bike-racks', [BikeRackController::class, 'index'])
        ->name('bike-racks.index');

    Route::get(
        '/confirmation-receipts',
        [ConfirmationReceiptController::class, 'index']
    )->name('confirmation-receipts.index');

    Route::post('/bike-racks', [BikeRackController::class, 'store'])
        ->name('bike-racks.store');

    Route::get('/bike-racks/{bike_rack}', [BikeRackController::class, 'show'])
        ->whereNumber('bike_rack')
        ->name('bike-racks.show');

    Route::patch('/bike-racks/{bike_rack}', [BikeRackController::class, 'update'])
        ->whereNumber('bike_rack')
        ->name('bike-racks.update');

    Route::delete('/bike-racks/{bike_rack}', [BikeRackController::class, 'destroy'])
        ->whereNumber('bike_rack')
        ->name('bike-racks.destroy');

    Route::post('/bike-racks/{bike_rack}/open', [BikeRackController::class, 'open'])
        ->whereNumber('bike_rack')
        ->name('bike-racks.open');

    Route::get('/bike-racks/{bike_rack}/receipts', [BikeRackController::class, 'receipts'])
        ->whereNumber('bike_rack')
        ->name('bike-racks.receipts.index');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});
