<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationApprovalController;

Route::get('/signup', function () {
    return view('signup');
});

Route::middleware(['auth'])->group(function () {
    // Admin views (blade, not API)
    Route::middleware('role:Admin,Supervisor')->group(function () {
        Route::get('/admin/registrations', [RegistrationApprovalController::class,'index'])->name('admin.registrations');
        Route::post('/admin/registrations/{id}/approve', [RegistrationApprovalController::class,'approve'])->name('admin.registrations.approve');
        Route::post('/admin/registrations/{id}/deny', [RegistrationApprovalController::class,'deny'])->name('admin.registrations.deny');
    });

    // Dashboard placeholder
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
require __DIR__.'/auth.php';


Route::get('/', function () {
    return view('home');
});



Route::get('/temp', function () {
    return view('template');
});

