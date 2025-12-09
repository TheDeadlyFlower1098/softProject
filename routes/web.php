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
| Patient Additional Info (Admin + Supervisor)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Admin,Supervisor'])->group(function () {
    Route::get('/patients/additional', function () {
        // Later you can pass a real $patient here.
        return view('patients_additional');
    })->name('patients.additional');
});

/*
// OPTIONAL: Patients list page (uncomment when you create patients.blade.php)
Route::middleware(['auth', 'role:Admin,Supervisor,Doctor,Caregiver'])->group(function () {
    Route::get('/patients', function () {
        return view('patients');
    })->name('patients');
});
*/

/*
|--------------------------------------------------------------------------
| Prescription / Appointment Routes
|--------------------------------------------------------------------------
*/

Route::post(
    '/appointments/{appointment}/prescriptions',
    [PrescriptionController::class, 'store']
)->name('appointments.prescriptions.store');

Route::get(
    '/appointments/{id}/details',
    [DoctorHomeController::class, 'appointmentDetails']
)->name('appointment.details');


/*
|--------------------------------------------------------------------------
| Public Routes (no auth)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dataviewer', [DataViewerController::class, 'index'])
    ->name('dataviewer');


/*
|--------------------------------------------------------------------------
| Guest Routes (only when NOT logged in)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    // Registration approval view (if you really want it visible when logged out)
    Route::get('/registration-approval', [RegistrationApprovalController::class, 'index'])
        ->name('registration.approval');

    Route::post('/registration-approval/{id}/approve',
        [RegistrationApprovalController::class, 'approve'])
        ->name('registration.approve');

    Route::post('/registration-approval/{id}/deny',
        [RegistrationApprovalController::class, 'deny'])
        ->name('registration.deny');

    // Login
    Route::get('/login', function () {
        return view('login');
    })->name('login');

    // Signup page (reuses welcome)
    Route::get('/signup', function () {
        return view('welcome');
    })->name('signup');

    // Handle login / signup
    Route::post('/login_attempt', [LoginAuthController::class, 'attempt'])
        ->name('login_attempt');

    Route::post('/signup', [RegistrationRequestController::class, 'store'])
        ->name('signup.store');

    // Example public-only page
    Route::get('/family-member', function () {
        return view('family_member');
    })->name('family.member');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes (any logged-in user)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Generic dashboards / views
    Route::get('/dashboard', fn () => view('patient_dashboard'))
        ->name('dashboard');

    Route::get('/home', fn () => view('home'))
        ->name('home');

    // Patient dashboard
    Route::get('/patient_dashboard', [PatientDashboardController::class, 'index'])
        ->name('patient.dashboard');

    // Patient saves single medicine record
    Route::post(
        '/patient_dashboard/medicine-check',
        [MedicineCheckController::class, 'saveSingle']
    )->name('medicinecheck.saveSingle');

    // Caregiver dashboard + save
    Route::get('/caregiver', [MedicineCheckController::class, 'dashboard'])
        ->name('caregiver.dashboard');

    Route::post('/caregiver/save-today',
        [MedicineCheckController::class, 'saveMultiple'])
        ->name('caregiver.saveToday');

    // Roster routes (controller itself can further restrict create/store)
    Route::get('/roster', [RosterController::class, 'dashboard'])
        ->name('roster.dashboard');

    Route::get('/roster/new', [RosterController::class, 'create'])
        ->name('roster.new');

    Route::post('/roster', [RosterController::class, 'store'])
        ->name('roster.store');

    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});


/*
|--------------------------------------------------------------------------
| Doctor Routes (Doctor role only)
|--------------------------------------------------------------------------
*/

// Doctor home route
Route::get('/doctorHome', [DoctorHomeController::class, 'index'])
    ->middleware(['auth', 'role:Doctor'])
    ->name('doctorHome');


/*
|--------------------------------------------------------------------------
| Doctor Appointment Creation (Admin + Supervisor)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:Admin,Supervisor'])->group(function () {

    // Appointment creation form
    Route::get(
        '/doctor-appointments',
        [DoctorHomeController::class, 'createAppointment']
    )->name('doctor.appointments');

    // Store appointment
    Route::post(
        '/doctor-appointments',
        [DoctorHomeController::class, 'storeAppointment']
    )->name('doctor.appointments.store');

    // AJAX patient lookup
    Route::get(
        '/api/patients/{patient}',
        [DoctorHomeController::class, 'lookupPatient']
    )->name('patients.lookup');
});


/*
|--------------------------------------------------------------------------
| Family Dashboard (Family role only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Family'])->group(function () {
    Route::get('/family-dashboard', [FamilyDashboardController::class, 'index'])
        ->name('family.dashboard');
});


/*
|--------------------------------------------------------------------------
| Admin-only Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->group(function () {

    // Payments
    Route::get('/payments', [PaymentController::class, 'adminIndex'])
        ->name('payments');

    Route::post('/payments/{patient}/pay',
        [PaymentController::class, 'recordPayment'])
        ->name('payments.pay');

    // Roles management
    Route::get('/roles', [RolesController::class, 'index'])
        ->name('roles.index');

    Route::post('/roles', [RolesController::class, 'store'])
        ->name('roles.store');

    Route::patch(
        '/roles/users/{user}',
        [RolesController::class, 'updateUserRole']
    )->name('roles.users.update');
});


/*
|--------------------------------------------------------------------------
| Admin / Supervisor Routes (Registration Approval, etc.)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin,Supervisor'])->group(function () {

    // Admin registrations list
    Route::get('/admin/registrations',
        [RegistrationApprovalController::class, 'index'])
        ->name('admin.registrations');

    // Admin registration approval page (same controller as above)
    Route::get('/admin/registration-approval',
        [RegistrationApprovalController::class, 'index'])
        ->name('registration.approval.admin');

    Route::post('/admin/registration-approval/{id}/approve',
        [RegistrationApprovalController::class, 'approve'])
        ->name('registration.approve.admin');

    Route::post('/admin/registration-approval/{id}/deny',
        [RegistrationApprovalController::class, 'deny'])
        ->name('registration.deny.admin');
});


/*
|--------------------------------------------------------------------------
| Auth scaffolding routes (Laravel default)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
