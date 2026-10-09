<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\WatchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WatchController::class, 'index'])->name('home');
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth', ['register' => false])->name('login');
    Route::view('/register', 'auth', ['register' => true]);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
});
Route::get('/communities', [WatchController::class, 'communities']);
Route::view('/advertise', 'sponsors.advertise');
Route::post('/advertise', [SponsorController::class, 'inquire'])->middleware('throttle:3,60');
Route::get('/sponsors/{sponsor}/image', [SponsorController::class, 'image']);
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/reports/create', [WatchController::class, 'create']);
    Route::post('/reports', [WatchController::class, 'store'])->middleware('throttle:10,60');
    Route::get('/my-reports', [WatchController::class, 'mine']);
    Route::post('/communities/{community}/join', [WatchController::class, 'join']);
    Route::get('/moderation', [WatchController::class, 'moderation']);
    Route::get('/moderation/sponsors', [SponsorController::class, 'manage']);
    Route::post('/moderation/sponsors', [SponsorController::class, 'store']);
    Route::post('/moderation/sponsors/{sponsor}/toggle', [SponsorController::class, 'toggle']);
    Route::post('/moderation/{report}', [WatchController::class, 'moderate']);
    Route::get('/moderation/{report}/tips', [WatchController::class, 'tips']);
});
Route::get('/reports/{report}', [WatchController::class, 'show'])->name('reports.show');
Route::get('/photos/{photo}', [WatchController::class, 'photo']);
Route::post('/reports/{report}/tips', [WatchController::class, 'tip'])->middleware('throttle:3,60');
