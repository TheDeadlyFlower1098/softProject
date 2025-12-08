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

// Doctor home route
Route::get('/doctorHome', [DoctorHomeController::class, 'index'])
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

// Admin approval page + actions (for registration requests)
Route::get(
    '/admin/registration-approval',
    [RegistrationRequestController::class, 'index']
)->name('registration.approval');

Route::post(
    '/admin/registration-approval/{id}/approve',
    [RegistrationRequestController::class, 'approve']
)->name('registration.approve');

Route::post(
    '/admin/registration-approval/{id}/deny',
    [RegistrationRequestController::class, 'deny']
)->name('registration.deny');

/*
|--------------------------------------------------------------------------
| Guest routes (not logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    // Login page
    Route::get('/login', function () {
        return view('login');
    })->name('login');

    // Signup page
    Route::get('/signup', function () {
        return view('welcome');
    })->name('signup');

    // Handle login / signup
    Route::post('/login_attempt', [LoginAuthController::class, 'attempt'])
        ->name('login_attempt');

    Route::post('/signup', [RegistrationRequestController::class, 'store'])
        ->name('signup.store');

    // (optional) public-only pages
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

    // Patient dashboard (used as "Patient Home")
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])
        ->name('dashboard');

    // Alternative patient dashboard route (if needed elsewhere)
    Route::get('/patient_dashboard', [PatientDashboardController::class, 'index'])
        ->name('patient.dashboard');

    // Patients list (points to patientsList.blade.php)
    Route::get('/patients', function () {
        return view('patientsList');
    })->name('patients');

    // Employees page
    Route::get('/employees', function () {
        return view('employees');
    })->name('employees');

    // Doctor appointments page
    Route::get('/doctor-appointments', function () {
        return view('doctor_appointments');
    })->name('doctor.appointments');

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
    | Admin report
    |--------------------------------------------------------------------------
    */
    Route::get('/admin-report', [ReportController::class, 'viewReportPage'])
        ->name('admin.report');

    Route::get('/admin-report/data', [ReportController::class, 'missedActivities']);
    // (add ->name('admin.report.data') later if you need a named route)

    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */
    Route::get('/payments', [PaymentController::class, 'showForm'])
        ->name('payments');

    Route::post('/payments', [PaymentController::class, 'calculateFromForm'])
        ->name('payments.calculate');

    /*
    |--------------------------------------------------------------------------
    | Roster routes
    |--------------------------------------------------------------------------
    */

    // Everyone logged in can view the roster dashboard
    Route::get('/roster', [RosterController::class, 'dashboard'])
        ->name('roster.dashboard');

    // New roster form (controller will check Admin / Supervisor role)
    Route::get('/roster/new', [RosterController::class, 'create'])
        ->name('roster.new');

    // Save roster (create / update)
    Route::post('/roster', [RosterController::class, 'store'])
        ->name('roster.store');

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
| Admin / Supervisor routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin,Supervisor'])->group(function () {
    Route::get('/admin/registrations', [RegistrationApprovalController::class, 'index'])
        ->name('admin.registrations');
});

/*
|--------------------------------------------------------------------------
| Auth scaffolding routes (Laravel default)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
