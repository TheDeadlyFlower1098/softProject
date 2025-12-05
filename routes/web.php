<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RegistrationApprovalController;
use App\Http\Controllers\RegistrationRequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LoginAuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\MedicineCheckController;
use App\Http\Controllers\FamilyDashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorHomeController;
use App\Http\Controllers\PrescriptionController;

/*
|--------------------------------------------------------------------------
| Routes related to prescriptions / appointments
|--------------------------------------------------------------------------
*/

// store prescription for an appointment
Route::post(
    '/appointments/{appointment}/prescriptions',
    [PrescriptionController::class, 'store']
)->name('appointments.prescriptions.store');

// view a single appointment's details
Route::get('/appointments/{id}/details', [DoctorHomeController::class, 'appointmentDetails'])
    ->name('appointment.details');

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dataviewer', [\App\Http\Controllers\DataViewerController::class, 'index']);

// Admin approval page + actions (registration requests)
Route::get('/admin/registration-approval', [RegistrationRequestController::class, 'index'])
    ->name('registration.approval');

Route::post('/admin/registration-approval/{id}/approve', [RegistrationRequestController::class, 'approve'])
    ->name('registration.approve');

Route::post('/admin/registration-approval/{id}/deny', [RegistrationRequestController::class, 'deny'])
    ->name('registration.deny');

/**
 * Doctor home route – uses DoctorHomeController
 */
Route::get('/doctorHome', [DoctorHomeController::class, 'index'])
    ->name('doctorHome');

Route::get('/appointment/{id}', [App\Http\Controllers\DoctorHomeController::class, 'appointmentDetails'])
    ->name('appointment.details');
/*
|--------------------------------------------------------------------------
| Guest routes (still public)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/home', function () {
        return redirect()->route('home');
    })->name('home');

    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::get('/signup', function () {
        return view('welcome');
    })->name('signup');

    Route::post('/login_attempt', [LoginAuthController::class, 'attempt'])
        ->name('login_attempt');

    Route::post('/signup', [RegistrationRequestController::class, 'store'])
        ->name('signup.store');

    Route::get('/family-member', function () {
        return view('family_member');
    })->name('family.member');

    // ADMIN / SUPERVISOR routes (change these to be in admin / supervisor section of routes once i know this is working)
    // Route::middleware(['auth', 'role:Admin,Supervisor'])->group(function () {
    //     // Approval list + search
    //     Route::get('/registration-approval', [RegistrationRequestController::class, 'index'])
    //         ->name('registration.approval');

    //     // Approve / Deny actions
    //     Route::post('/registration-approval/{id}/approve', [RegistrationRequestController::class, 'approve'])
    //         ->name('registration.approve');

    //     Route::post('/registration-approval/{id}/deny', [RegistrationRequestController::class, 'deny'])
    //         ->name('registration.deny');
    // });

    // PUBLIC – for testing
    Route::get('/registration-approval', [RegistrationApprovalController::class, 'index'])
        ->name('registration.approval');

    Route::post('/registration-approval/{id}/approve', [RegistrationApprovalController::class, 'approve'])
        ->name('registration.approve');

    Route::post('/registration-approval/{id}/deny', [RegistrationApprovalController::class, 'deny'])
        ->name('registration.deny');

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

    // Main app pages
    Route::get('/employees', function () {
        return view('employees');
    })->name('employees');

    Route::post('/patient_dashboard/medicine-check', [MedicineCheckController::class, 'saveForTodayFromDashboard'])
        ->name('medicinecheck.saveToday');

    Route::post('/medicine-check', [MedicineCheckController::class, 'store'])
        ->name('medicinecheck.store');

    Route::get('/doctor-appointments', function () {
        return view('doctor_appointments');
    })->name('doctor.appointments');

    Route::get('/new-roster', function () {
        return view('new_roster');
    })->name('new.roster');

    Route::get('/patient_dashboard', [PatientDashboardController::class, 'index'])
        ->name('patient.dashboard');

    Route::get('/supervisor-roster', function () {
        return view('supervisor_roster');
    })->name('supervisor.roster');

    Route::middleware(['role:Family'])->group(function () {
        Route::get('/family-dashboard', [FamilyDashboardController::class, 'index'])
            ->name('family.dashboard');
    });

    Route::get('/admin-report', [ReportController::class, 'viewReportPage'])
        ->name('admin.report');

    Route::get('/admin-report/data', [ReportController::class, 'missedActivities']);
    // (you can add ->name('admin.report.data') if you ever need to generate this URL)

    Route::get('/patients', function () {
        return view('patients');
    })->name('patients');

    Route::post('/logout', function () {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin / Supervisor routes
|--------------------------------------------------------------------------
*/

Route::middleware('role:Admin,Supervisor')->group(function () {
    Route::get('/admin/registrations', [RegistrationApprovalController::class, 'index'])
        ->name('admin.registrations');
});

/*
|--------------------------------------------------------------------------
| Auth scaffolding routes (login, register, etc.)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
