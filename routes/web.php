<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegistrationApprovalController;
use App\Http\Controllers\RegistrationRequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LoginAuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\MedicineCheckController;
use App\Http\Controllers\FamilyDashboardController;
use App\Http\Controllers\DoctorHomeController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\RosterController;
use App\Http\Controllers\DataViewerController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\RolesController;

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
Route::get(
    '/appointments/{id}/details',
    [DoctorHomeController::class, 'appointmentDetails']
)->name('appointment.details');

// Doctor home route (doctor only)
Route::get('/doctorHome', [DoctorHomeController::class, 'index'])
    ->middleware(['auth', 'role:Doctor'])
    ->name('doctorHome');

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

// Landing page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Data viewer
Route::get('/dataviewer', [DataViewerController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Guest routes (not logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/registration-approval', function() {
        return view('registration_approval');
    });

    Route::get('/registration-approval', [RegistrationApprovalController::class, 'index'])
        ->name('registration.approval');

    // approve a specific request
    Route::post('/registration-approval/{id}/approve', [RegistrationApprovalController::class, 'approve'])
        ->name('registration.approve');

    // deny a specific request
    Route::post('/registration-approval/{id}/deny', [RegistrationApprovalController::class, 'deny'])
        ->name('registration.deny');

    // Login page
    Route::get('/login', function () {
        return view('login');
    })->name('login');

    // Signup page (uses welcome with signup form)
    Route::get('/signup', function () {
        return view('welcome');
    })->name('signup');

    // Handle login / signup
    Route::post('/login_attempt', [LoginAuthController::class, 'attempt'])
        ->name('login_attempt');

    Route::post('/signup', [RegistrationRequestController::class, 'store'])
        ->name('signup.store');

    // (optional) public-only page example
    Route::get('/family-member', function () {
        return view('family_member');
    })->name('family.member');
});

/*
|--------------------------------------------------------------------------
| Authenticated routes (logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Home page for logged-in users
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    /*
    |--------------------------------------------------------------------------
    | Patient dashboards
    |--------------------------------------------------------------------------
    */
    // Patient dashboard (used as "Patient Home")
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])
        ->middleware('role:Patient')
        ->name('dashboard');

    // Alternative patient dashboard route (if needed elsewhere)
    Route::get('/patient_dashboard', [PatientDashboardController::class, 'index'])
        ->middleware('role:Patient')
        ->name('patient.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Patients and additional information (Admin/Supervisor/Doctor/Caregiver)
    |--------------------------------------------------------------------------
    */
    // Patients list (points to patientsList.blade.php)
    Route::get('/patients', function () {
        return view('patientsList');
    })->middleware('role:Admin,Supervisor,Doctor,Caregiver')
      ->name('patients');

    // Additional patient information page (Admin & Supervisor only)
    Route::get('/patients/additional', function () {
        return view('patientAdditional');
    })->middleware('role:Admin,Supervisor')
      ->name('patients.additional');

    /*
    |--------------------------------------------------------------------------
    | Employees (Admin & Supervisor)
    |--------------------------------------------------------------------------
    */
    Route::get('/employees', [EmployeeController::class, 'index'])
        ->middleware('role:Admin,Supervisor')
        ->name('employees');

    Route::get('/employees/filter', [EmployeeController::class, 'filtered'])
        ->middleware('role:Admin,Supervisor');

    Route::put('/employees/{id}', [EmployeeController::class, 'update'])
        ->middleware('role:Admin,Supervisor');

    /*
    |--------------------------------------------------------------------------
    | Doctor appointments (created by Admin & Supervisor)
    |--------------------------------------------------------------------------
    */
    Route::get('/doctor-appointments', function () {
        return view('doctor_appointments');
    })->middleware('role:Admin,Supervisor')
      ->name('doctor.appointments');

    /*
    |--------------------------------------------------------------------------
    | Medicine check routes
    |--------------------------------------------------------------------------
    */
    Route::post(
        '/patient_dashboard/medicine-check',
        [MedicineCheckController::class, 'saveForTodayFromDashboard']
    )->name('medicinecheck.saveToday');

    Route::post(
        '/medicine-check',
        [MedicineCheckController::class, 'store']
    )->name('medicinecheck.store');

    /*
    |--------------------------------------------------------------------------
    | Family & Caregiver dashboards
    |--------------------------------------------------------------------------
    */

    // Family dashboard (only Family role)
    Route::middleware('role:Family')->group(function () {
        Route::get(
            '/family-dashboard',
            [FamilyDashboardController::class, 'index']
        )->name('family.home');
    });

    // Caregiver dashboard
    Route::get('/caregiver-dashboard', function () {
        $user = auth()->user();

        if (! $user || $user->role->name !== 'Caregiver') {
            abort(403);
        }

        return view('caregiver_dashboard');
    })->name('caregiver.home');

    /*
    |--------------------------------------------------------------------------
    | Admin report (Admin & Supervisor)
    |--------------------------------------------------------------------------
    */
    Route::get('/admin-report', [ReportController::class, 'viewReportPage'])
        ->middleware('role:Admin,Supervisor')
        ->name('admin.report');

    Route::get('/admin-report/data', [ReportController::class, 'missedActivities'])
        ->middleware('role:Admin,Supervisor');

    /*
    |--------------------------------------------------------------------------
    | Payments (Admin only)
    |--------------------------------------------------------------------------
    */
    

    Route::middleware(['auth', 'role:Admin'])->group(function () {
        // List all patients + payments
        Route::get('/payments', [PaymentController::class, 'adminIndex'])
            ->name('payments');

        // Record a payment for a specific patient
        Route::post('/payments/{patient}/pay', [PaymentController::class, 'recordPayment'])
            ->name('payments.pay');
    });



    /*
    |--------------------------------------------------------------------------
    | Roster routes
    |--------------------------------------------------------------------------
    */

    // Everyone logged in can view the roster dashboard
    Route::get('/roster', [RosterController::class, 'dashboard'])
        ->name('roster.dashboard');

    // New roster form (Admin / Supervisor)
    Route::get('/roster/new', [RosterController::class, 'create'])
        ->middleware('role:Admin,Supervisor')
        ->name('roster.new');

    // Save roster (create / update)
    Route::post('/roster', [RosterController::class, 'store'])
        ->middleware('role:Admin,Supervisor')
        ->name('roster.store');

    /*
    |--------------------------------------------------------------------------
    | Roles page (Admin only)
    |--------------------------------------------------------------------------
    */
    Route::get('/roles', function () {
        return view('roles');
    })->middleware('role:Admin')
      ->name('roles.index');

    Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');
    Route::post('/roles', [RolesController::class, 'store'])->name('roles.store');
    Route::patch('/roles/users/{user}', [RolesController::class, 'updateUserRole'])
        ->name('roles.users.update');

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', function () {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin / Supervisor routes for Registration Approval
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin,Supervisor'])->group(function () {
    Route::get(
        '/registration-approval',
        [RegistrationApprovalController::class, 'index']
    )->name('registration.approval');

    Route::post(
        '/registration-approval/{id}/approve',
        [RegistrationApprovalController::class, 'approve']
    )->name('registration.approve');

    Route::post(
        '/registration-approval/{id}/deny',
        [RegistrationApprovalController::class, 'deny']
    )->name('registration.deny');
});

/*
|--------------------------------------------------------------------------
| Auth scaffolding routes (Laravel default)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
