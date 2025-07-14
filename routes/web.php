<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [LandingPageController::class, 'index'])->name('landing');


Route::get('/api/shop-hours', [BookingController::class, 'getShopHours']);


Route::get('/api/booking-status/{booking}', [BookingController::class, 'getBookingStatus'])->name('api.booking.status');





require __DIR__.'/auth.php';



Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', function () {
        return redirect()->route('landing');
    })->name('dashboard');

    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/history', [BookingController::class, 'history'])->name('booking.history');

    
    
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::get('/booking/{booking}/edit', [BookingController::class, 'edit'])->name('booking.edit');
    Route::put('/booking/{booking}', [BookingController::class, 'update'])->name('booking.update');
    Route::delete('/booking/{booking}', [BookingController::class, 'destroy'])->name('booking.destroy'); 
});







