<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationApprovalController;
use App\Http\Controllers\RegistrationRequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LoginAuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PatientDashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MedicineCheckController;
use App\Http\Controllers\FamilyDashboardController;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dataviewer', [App\Http\Controllers\DataViewerController::class, 'index']);

Route::get('/registration-approval', function () {
    return view('registration_approval');
});

Route::get('/registration-approval', [RegistrationApprovalController::class, 'index'])
    ->name('registration.approval');

Route::post('/registration-approval/approve/{id}', [RegistrationApprovalController::class, 'approve'])
    ->name('registration.approve');

Route::post('/registration-approval/deny/{id}', [RegistrationApprovalController::class, 'deny'])
    ->name('registration.deny');

/*
|--------------------------------------------------------------------------
| Guest routes (still public)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::get('/signup', function () {
        return view('signup');
    })->name('signup');

    Route::post('/login_attempt', [LoginAuthController::class, 'attempt'])
        ->name('login_attempt');

    Route::post('/signup', [RegistrationRequestController::class, 'store'])
        ->name('signup.store');

    Route::get('/family-member', function () {
        return view('family_member');
    })->name('family.member');
});
    
/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard / home
    Route::get('/dashboard', function () {
        return view('patient_dashboard');
    })->name('dashboard');

    Route::post('/patient_dashboard/medicine-check', [MedicineCheckController::class, 'saveForTodayFromDashboard'])
        ->middleware('auth')
        ->name('medicinecheck.saveToday');

    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // Main app pages
    Route::get('/employees', function () {
        return view('employees');
    })->name('employees');

    Route::get('/patients', function () {
        return view('patients');
    })->name('patients');

    Route::post('/medicine-check', [MedicineCheckController::class, 'store'])
    ->name('medicinecheck.store');

    Route::get('/doctor-appointments', function () {
        return view('doctor_appointments');
    })->name('doctor.appointments');

    Route::get('/admin-report', [ReportController::class, 'viewReportPage'])->name('admin.report');
    Route::get('/admin-report/data', [ReportController::class, 'missedActivities']);

    Route::get('/patient_dashboard', [PatientDashboardController::class, 'index'])
        ->middleware('auth')
        ->name('patient.dashboard');

    Route::get('/new-roster', function () {
        return view('new_roster');
    })->name('new.roster');

    Route::get('/roster', function () {
        return view('roster');
    })->name('roster');

    Route::get('/supervisor-roster', function () {
        return view('supervisor_roster');
    })->name('supervisor.roster');

    Route::middleware(['auth', 'role:Family'])->group(function () {
        Route::get('/family-dashboard', [\App\Http\Controllers\FamilyDashboardController::class, 'index'])
            ->name('family.dashboard');
    });

    Route::post('/logout', function () {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/'); // or wherever you want after logout
    })->name('logout');
});

    /*
    |--------------------------------------------------------------------------
    | Admin / Supervisor routes
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Admin,Supervisor')->group(function () {
        Route::get('/admin/registrations', [RegistrationApprovalController::class,'index'])
            ->name('admin.registrations');
      
    // Route::middleware('role:Admin,Supervisor')->group(function () {
    //     Route::get('/admin/registrations', [RegistrationApprovalController::class,'index'])
    //         ->name('admin.registrations');

    //     Route::post('/admin/registrations/{id}/approve', [RegistrationApprovalController::class,'approve'])
    //         ->name('admin.registrations.approve');

    //     Route::post('/admin/registrations/{id}/deny', [RegistrationApprovalController::class,'deny'])
    //         ->name('admin.registrations.deny');
    // });
    });

/*
|--------------------------------------------------------------------------
| Auth scaffolding routes (login, register, etc.)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';




Route::get('/dataviewer', [App\Http\Controllers\DataViewerController::class, 'index'])
    ->name('dataviewer');

