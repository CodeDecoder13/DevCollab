<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/about', fn () => Inertia::render('About'))->name('about');
Route::get('/features', fn () => Inertia::render('Features'))->name('features');
Route::get('/solutions', fn () => Inertia::render('Solutions'))->name('solutions');
Route::get('/guide', fn () => Inertia::render('Guide'))->name('guide');
Route::get('/contact', fn () => Inertia::render('Contact'))->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('search', SearchController::class)->name('search');

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

require __DIR__.'/settings.php';
require __DIR__.'/projects.php';
require __DIR__.'/admin.php';
