<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Support Request Routes
    Route::resource('support-requests', SupportRequestController::class);
    
    // Comments
    Route::post('comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Service Schedules
    Route::post('service-schedules', [ServiceScheduleController::class, 'store'])->name('service-schedules.store');
    Route::patch('service-schedules/{schedule}', [ServiceScheduleController::class, 'update'])->name('service-schedules.update');

    // Satisfaction Ratings
    Route::post('satisfaction-ratings', [SatisfactionRatingController::class, 'store'])->name('satisfaction-ratings.store');
});

require __DIR__.'/auth.php';
