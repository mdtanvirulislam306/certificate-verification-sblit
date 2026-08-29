<?php

use App\Http\Controllers\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\CertificateVerificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Landing');
})->name('home');

Route::get('/verify/{code}', [CertificateVerificationController::class, 'show'])->name('verify.show');

Route::redirect('/dashboard', '/admin')->name('dashboard.redirect');

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('courses', \App\Http\Controllers\Admin\CourseController::class)->except(['show']);
    Route::get('certificates/{certificate}/generate', [AdminCertificateController::class, 'generate'])
        ->name('certificates.generate');
    Route::resource('certificates', AdminCertificateController::class)->except(['show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
